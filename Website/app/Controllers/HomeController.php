<?php

namespace App\Controllers;

use App\Models\StudySession;
use App\Models\TutorProfile;
use App\Models\StudySubject;
use App\Models\StudyProgram;
use App\Models\Review;

class HomeController
{
    public function index(): void
    {
        // 1. Real Database Statistics
        $stats = StudySession::getPlatformStats();

        // 2. Featured Tutors from Database
        $featuredTutors = TutorProfile::getFeaturedTutors(4);

        // 3. Popular Subjects from Database
        $popularSubjects = StudySubject::getPopularSubjects(6);

        // 4. Study Programs for search filter
        $studyPrograms = StudyProgram::all();

        // 5. Recent Authentic Reviews from Database
        $testimonials = Review::getAllAdmin(1, 3);

        view('home/index', [
            'title' => 'TemanBelajar — Platform Tutor Sebaya Mahasiswa',
            'stats' => $stats,
            'featuredTutors' => $featuredTutors,
            'popularSubjects' => $popularSubjects,
            'studyPrograms' => $studyPrograms,
            'testimonials' => $testimonials,
        ]);
    }
}
