<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class SidebarService
{
    /**
     * Get dynamic department sections for sidebar
     * Only returns departments user has access to
     */
    public static function getDepartmentSections()
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // Get all active departments
        $departments = Department::where('active', true)
            ->orderBy('name_fr')
            ->get();

        $sections = [];

        foreach ($departments as $dept) {
            // Check if user has permission to see this department
            if (!$user->canSeeDepartment($dept->permission)) {
                continue;
            }

            $section = [
                'id' => $dept->id,
                'name' => $dept->name_fr ?? $dept->name,
                'permission' => $dept->permission,
                'slug' => $dept->slug ?? strtolower(str_replace(' ', '_', $dept->name)),
                'icon' => $dept->icon ?? 'icon-' . strtolower($dept->name),
            ];

            // Add department-specific sub-routes based on department type
            $section['routes'] = self::getRoutesByDepartment($dept);

            $sections[] = $section;
        }

        return $sections;
    }

    /**
     * Get routes for a specific department
     * This maps each department to its relevant pages/modules
     */
    private static function getRoutesByDepartment(Department $dept)
    {
        $slug = $dept->slug ?? strtolower(str_replace(' ', '_', $dept->name));

        $departmentRoutes = [
            'musique_danse' => [
                ['name' => 'Cartes professionnelles', 'route' => 'admin.carteProfessionnelle.index', 'icon' => 'icon-carte'],
                ['name' => 'Diplômes musicaux', 'route' => 'admin.diplomesMusicaux.index', 'icon' => 'icon-diplome'],
                ['name' => 'Impresarios & Contrats', 'route' => 'admin.impresariosContrats.index', 'icon' => 'icon-contrat'],
                ['name' => 'Patrimoine musical', 'route' => 'admin.patrimoineMusical.index', 'icon' => 'icon-patrimoine'],
                ['name' => 'Vérification CNSS', 'route' => 'admin.verificationCNSS.index', 'icon' => 'icon-cnss'],
                ['name' => 'Workflow Musique & Danse', 'route' => 'admin.workflowMusicetDance.index', 'icon' => 'icon-workflow'],
            ],
            'arts_plastiques' => [
                ['name' => 'Dashboard Arts Plastiques', 'route' => 'admin.dashboard-art-plastiques.index', 'icon' => 'icon-dashboard'],
                ['name' => 'Frames', 'route' => 'admin.frames_Arts_Plastiques.index', 'icon' => 'icon-frame'],
                ['name' => 'Accès FNAP', 'route' => 'admin.accessFNAP_Arts_Plastiques.index', 'icon' => 'icon-fnap'],
                ['name' => 'Artistes étrangers', 'route' => 'admin.artistes_Arts_Plastiques.index', 'icon' => 'icon-artiste'],
                ['name' => 'Prises de vue d\'œuvres', 'route' => 'admin.photos_Arts_Plastiques.index', 'icon' => 'icon-photo'],
                ['name' => 'Gestion des conflits', 'route' => 'admin.gestionConflits_Arts_Plastiques.index', 'icon' => 'icon-conflit'],
                ['name' => 'Analytics & Rapports', 'route' => 'admin.analytics_Arts_Plastiques.index', 'icon' => 'icon-analytics'],
                ['name' => 'Journal d\'audit', 'route' => 'admin.auditLog_Arts_Plastiques.index', 'icon' => 'icon-audit'],
                ['name' => 'Gestion des documents', 'route' => 'admin.documents_Arts_Plastiques.index', 'icon' => 'icon-documents'],
                ['name' => 'Workflow Arts Plastiques', 'route' => 'admin.workflowArtsPlastiques.index', 'icon' => 'icon-workflow'],
            ],
            'livre' => [
                ['name' => 'Facilitation transfert droits', 'route' => 'admin.livre.droits.index', 'icon' => 'icon-droits'],
                ['name' => 'Participation foire internationale', 'route' => 'admin.livre.foire.index', 'icon' => 'icon-foire'],
                ['name' => 'Couverture frais transport', 'route' => 'admin.livre.transport.index', 'icon' => 'icon-transport'],
                ['name' => 'Matériaux exonérés TVA', 'route' => 'admin.livre.tva.index', 'icon' => 'icon-tva'],
                ['name' => 'Workflow - Direction Générale du Livre', 'route' => 'admin.livre.workflow.index', 'icon' => 'icon-workflow'],
            ],
            'investisseurs' => [
                ['name' => 'Attestation mécénat culturel', 'route' => 'admin.investisseurs.mecenat.index', 'icon' => 'icon-mecenat'],
                ['name' => 'Demande agrément', 'route' => 'admin.investisseurs.agrement.index', 'icon' => 'icon-agrement'],
                ['name' => 'Certification investisseur', 'route' => 'admin.investisseurs.certification.index', 'icon' => 'icon-certification'],
                ['name' => 'Workflow - Encadrement des investisseurs', 'route' => 'admin.investisseurs.workflow.index', 'icon' => 'icon-workflow'],
            ],
        ];

        return $departmentRoutes[$slug] ?? [];
    }

    /**
     * Check if a department exists and is active
     */
    public static function isDepartmentActive($slug)
    {
        return Department::where('active', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('name', $slug);
            })
            ->exists();
    }

    /**
     * Get department by slug
     */
    public static function getDepartmentBySlug($slug)
    {
        return Department::where('active', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('name', $slug);
            })
            ->first();
    }
}
