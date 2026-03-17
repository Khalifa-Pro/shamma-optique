<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $ventes = Vente::with('client')
            ->when($search, fn($q) => $q
                ->where('numero', 'like', "%$search%")
                ->orWhereHas('client', fn($q) => $q
                    ->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                )
            )
            ->latest('date_paiement')
            ->paginate(15)
            ->withQueryString();

        $totalCA = Vente::sum('montant_total');
        $caMonth = Vente::whereYear('date_paiement', now()->year)
                        ->whereMonth('date_paiement', now()->month)
                        ->sum('montant_total');

        return view('ventes.index', compact('ventes', 'search', 'totalCA', 'caMonth'));
    }

    // ─────────────────────────────────────────────────────
    // EXPORT EXCEL — Admin seulement (protégé par route middleware)
    // ─────────────────────────────────────────────────────
    public function export(Request $request)
    {
        $periode = $request->get('periode', 'mensuel');
        $now     = Carbon::now();

        // ── 1. Plage de dates selon la période ───────────
        switch ($periode) {
            case 'journalier':
                $debut        = $now->copy()->startOfDay();
                $fin          = $now->copy()->endOfDay();
                $labelPeriode = 'Journalier — ' . $now->format('d/m/Y');
                $nomFichier   = 'ventes_' . $now->format('Y-m-d');
                break;

            case 'hebdomadaire':
                $debut        = $now->copy()->startOfWeek();
                $fin          = $now->copy()->endOfWeek();
                $labelPeriode = 'Hebdomadaire — Semaine ' . $now->weekOfYear
                              . ' (' . $debut->format('d/m') . ' – ' . $fin->format('d/m/Y') . ')';
                $nomFichier   = 'ventes_semaine_' . $now->weekOfYear . '_' . $now->year;
                break;

            case 'annuel':
                $debut        = $now->copy()->startOfYear();
                $fin          = $now->copy()->endOfYear();
                $labelPeriode = 'Annuel — ' . $now->year;
                $nomFichier   = 'ventes_annuel_' . $now->year;
                break;

            default: // mensuel
                $debut        = $now->copy()->startOfMonth();
                $fin          = $now->copy()->endOfMonth();
                $labelPeriode = 'Mensuel — ' . ucfirst($now->locale('fr')->isoFormat('MMMM YYYY'));
                $nomFichier   = 'ventes_' . $now->format('Y-m');
        }

        // ── 2. Requête des ventes ────────────────────────
        $ventes = Vente::with(['client', 'facture'])
            ->whereBetween('date_paiement', [$debut, $fin])
            ->orderBy('date_paiement')
            ->get();

        $totalCA         = $ventes->sum('montant_total');
        $totalPartClient = $ventes->sum('part_client');
        $totalAssurance  = $ventes->sum('part_assurance');

        // ── 3. Construire le fichier Excel ───────────────
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ventes');

        // Palette
        $BLEU_FONCE = '0F2447';
        $BLEU_CLAIR = '1D9BF0';
        $BLEU_PALE  = 'DBEAFE';
        $BLANC      = 'FFFFFF';
        $GRIS_LEGER = 'F9FAFB';
        $GRIS_BORD  = 'E5E7EB';

        // ── Ligne 1 : Titre ───────────────────────────────
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'SHAMMA OPTIQUE — Rapport des Ventes');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => $BLANC]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $BLEU_FONCE]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(34);

        // ── Ligne 2 : Sous-titre période ──────────────────
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', $labelPeriode . '   |   Exporté le ' . now()->format('d/m/Y à H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'italic' => true, 'color' => ['rgb' => $BLANC]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $BLEU_CLAIR]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // ── Ligne 3 : Espacement ──────────────────────────
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Lignes 4-6 : Résumé financier ─────────────────
        $resume = [
            4 => ['CA TOTAL',                 $totalCA,         true],
            5 => ['Part clients',              $totalPartClient, false],
            6 => ['Part assurance / MCI CARE', $totalAssurance,  false],
        ];

        foreach ($resume as $ligne => [$label, $valeur, $isTotal]) {
            $sheet->mergeCells("A{$ligne}:E{$ligne}");
            $sheet->mergeCells("F{$ligne}:H{$ligne}");
            $sheet->setCellValue("A{$ligne}", $label);
            $sheet->setCellValue("F{$ligne}", $valeur);

            $sheet->getStyle("A{$ligne}:H{$ligne}")->applyFromArray([
                'font' => [
                    'bold'  => $isTotal,
                    'size'  => $isTotal ? 12 : 10,
                    'color' => ['rgb' => $isTotal ? $BLANC : '374151'],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $isTotal ? $BLEU_FONCE : $BLEU_PALE],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("F{$ligne}:H{$ligne}")->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                'font'      => ['bold' => $isTotal],
            ]);
            $sheet->getStyle("F{$ligne}")
                  ->getNumberFormat()
                  ->setFormatCode('#,##0 "FCFA"');

            $sheet->getRowDimension($ligne)->setRowHeight(22);
        }

        // ── Ligne 7 : Espacement ──────────────────────────
        $sheet->getRowDimension(7)->setRowHeight(6);

        // ── Ligne 8 : En-têtes tableau ────────────────────
        $entetes = [
            'A' => 'N° Vente',
            'B' => 'Client',
            'C' => 'Mode paiement',
            'D' => 'Date paiement',
            'E' => 'Part client (FCFA)',
            'F' => 'Part assurance (FCFA)',
            'G' => 'Total (FCFA)',
            'H' => 'Facture réf.',
        ];

        foreach ($entetes as $col => $entete) {
            $sheet->setCellValue("{$col}8", $entete);
        }

        $sheet->getStyle('A8:H8')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => $BLANC]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $BLEU_FONCE]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => $BLANC],
                ],
            ],
        ]);
        $sheet->getRowDimension(8)->setRowHeight(28);

        // ── Lignes de données ─────────────────────────────
        $ligneDebut = 9;

        foreach ($ventes as $idx => $vente) {
            $ligne   = $ligneDebut + $idx;
            $estPair = $idx % 2 === 0;
            $bgColor = $estPair ? $GRIS_LEGER : $BLANC;

            $sheet->setCellValue("A{$ligne}", $vente->numero);
            $sheet->setCellValue("B{$ligne}", $vente->client->full_name);
            $sheet->setCellValue("C{$ligne}", $vente->mode_paiement_label);
            $sheet->setCellValue("D{$ligne}", $vente->date_paiement->format('d/m/Y'));
            $sheet->setCellValue("E{$ligne}", $vente->part_client);
            $sheet->setCellValue("F{$ligne}", $vente->part_assurance);
            $sheet->setCellValue("G{$ligne}", $vente->montant_total);
            $sheet->setCellValue("H{$ligne}", $vente->facture?->numero ?? '—');

            // Style de la ligne
            $sheet->getStyle("A{$ligne}:H{$ligne}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => $GRIS_BORD],
                    ],
                ],
                'font'      => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);

            // Format numérique colonnes E, F, G
            foreach (['E', 'F', 'G'] as $col) {
                $sheet->getStyle("{$col}{$ligne}")
                      ->getNumberFormat()
                      ->setFormatCode('#,##0 "FCFA"');
                $sheet->getStyle("{$col}{$ligne}")
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            $sheet->getRowDimension($ligne)->setRowHeight(18);
        }

        // ── Ligne de totaux finale ────────────────────────
        $ligneTotaux = $ligneDebut + $ventes->count();

        $sheet->mergeCells("A{$ligneTotaux}:D{$ligneTotaux}");
        $sheet->setCellValue("A{$ligneTotaux}", 'TOTAL — ' . $ventes->count() . ' vente(s)');
        $sheet->setCellValue("E{$ligneTotaux}", $totalPartClient);
        $sheet->setCellValue("F{$ligneTotaux}", $totalAssurance);
        $sheet->setCellValue("G{$ligneTotaux}", $totalCA);
        $sheet->setCellValue("H{$ligneTotaux}", '');

        $sheet->getStyle("A{$ligneTotaux}:H{$ligneTotaux}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $BLANC]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $BLEU_FONCE]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => $BLANC],
                ],
            ],
        ]);

        foreach (['E', 'F', 'G'] as $col) {
            $sheet->getStyle("{$col}{$ligneTotaux}")
                  ->getNumberFormat()
                  ->setFormatCode('#,##0 "FCFA"');
            $sheet->getStyle("{$col}{$ligneTotaux}")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
        $sheet->getRowDimension($ligneTotaux)->setRowHeight(24);

        // ── Largeurs des colonnes ─────────────────────────
        $largeurs = [
            'A' => 18,
            'B' => 30,
            'C' => 20,
            'D' => 16,
            'E' => 20,
            'F' => 24,
            'G' => 18,
            'H' => 18,
        ];
        foreach ($largeurs as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Figer l'en-tête tableau
        $sheet->freezePane('A9');

        // ── Télécharger ───────────────────────────────────
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $nomFichier . '.xlsx',
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $nomFichier . '.xlsx"',
                'Cache-Control'       => 'max-age=0',
            ]
        );
    }
}
