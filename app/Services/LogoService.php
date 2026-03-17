<?php
// app/Services/LogoService.php

namespace App\Services;

class LogoService
{
    /**
     * Retourne le logo encodé en base64 pour DomPDF
     * Cherche dans plusieurs emplacements possibles
     */
    public static function base64(): string
    {
        $nomsFichiers = [
            'SHAMMA_OPTIQUE_LOGO.png',
            'SHAMMA_OPTIQUE_LOGO.PNG',
            'logo.png',
            'logo.jpg',
            'logo.jpeg',
        ];

        $dossiers = [
            public_path('asset/img'),
            public_path('assets/img'),
            public_path('img'),
            public_path('images'),
            storage_path('app/public'),
            base_path('public/asset/img'),
            base_path('public/assets/img'),
        ];

        foreach ($dossiers as $dossier) {
            foreach ($nomsFichiers as $nom) {
                $chemin = $dossier . DIRECTORY_SEPARATOR . $nom;
                if (file_exists($chemin) && is_readable($chemin)) {
                    $extension = strtolower(pathinfo($chemin, PATHINFO_EXTENSION));
                    $mime = match($extension) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'gif'         => 'image/gif',
                        'webp'        => 'image/webp',
                        default       => 'image/png',
                    };
                    $contenu = file_get_contents($chemin);
                    if ($contenu !== false) {
                        return 'data:' . $mime . ';base64,' . base64_encode($contenu);
                    }
                }
            }
        }

        // ── Fallback ultime : logo SVG inline encodé en base64 ──
        // Si aucun fichier trouvé, retourne un SVG texte comme image
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="60" viewBox="0 0 200 60">'
             . '<rect width="200" height="60" fill="#1a5276" rx="4"/>'
             . '<text x="100" y="22" font-family="Arial" font-size="14" font-weight="bold" fill="white" text-anchor="middle">SHAMMA OPTIQUE</text>'
             . '<text x="100" y="40" font-family="Arial" font-size="9" fill="#aed6f1" text-anchor="middle">Optique · Optométrie · Contactologie</text>'
             . '<text x="100" y="54" font-family="Arial" font-size="8" fill="#aed6f1" text-anchor="middle">Une vision de lynx</text>'
             . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
