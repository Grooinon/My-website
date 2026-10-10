<?php

namespace App\Controllers;

use App\Models\TutorProfile;
use App\Models\StudyProgram;
use App\Models\StudySubject;
use App\Models\Review;
use App\Helpers\Auth;

class TutorController
{
    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $programId = (int) ($_GET['program_id'] ?? 0);
        $subjectId = (int) ($_GET['subject_id'] ?? 0);
        $method = trim($_GET['method'] ?? 'all');
        $priceType = trim($_GET['price_type'] ?? '');
        $minRating = (float) ($_GET['min_rating'] ?? 0);
        $available = isset($_GET['available']) && $_GET['available'] !== '' ? (int) $_GET['available'] : '';
        $sort = trim($_GET['sort'] ?? 'rating_desc');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 9;

        $filters = [
            'search' => $search,
            'program_id' => $programId ?: null,
            'subject_id' => $subjectId ?: null,
            'method' => $method,
            'price_type' => $priceType,
            'min_rating' => $minRating ?: null,
            'available' => $available,
            'sort' => $sort,
        ];

        $tutors = TutorProfile::getAllWithFilters($filters, $page, $perPage);
        $totalTutors = TutorProfile::countWithFilters($filters);
        $totalPages = ceil($totalTutors / $perPage);

        $studyPrograms = StudyProgram::all();
        $studySubjects = StudySubject::all();

        view('tutors/index', [
            'title' => 'Cari Tutor Sebaya — TemanBelajar',
            'tutors' => $tutors,
            'totalTutors' => $totalTutors,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
            'studyPrograms' => $studyPrograms,
            'studySubjects' => $studySubjects,
        ]);
    }

    public function show(int $id): void
    {
        $tutor = TutorProfile::findById($id);

        if (!$tutor) {
            http_response_code(404);
            view('errors/404', ['title' => 'Tutor Tidak Ditemukan - 404']);
            return;
        }

        $subjects = TutorProfile::getSubjects($id);
        $availability = TutorProfile::getAvailability($id);
        $reviews = Review::getForTutor((int) $tutor['user_id'], 20);

        // Calculate dynamic average rating from valid reviews
        $avgRating = 0;
        $reviewCount = count($reviews);
        if ($reviewCount > 0) {
            $sum = array_sum(array_column($reviews, 'rating'));
            $avgRating = round($sum / $reviewCount, 1);
        }

        view('tutors/show', [
            'title' => "Profil Tutor {$tutor['full_name']} — TemanBelajar",
            'tutor' => $tutor,
            'subjects' => $subjects,
            'availability' => $availability,
            'reviews' => $reviews,
            'avgRating' => $avgRating,
            'reviewCount' => $reviewCount,
            'isSelf' => Auth::id() === (int) $tutor['user_id'],
        ]);
    }
}
