<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Quiz;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */

 class QuizFactory extends Factory
 {
     protected $model = Quiz::class;
 
     public function definition()
     {
         return [
             'title' => $this->faker->sentence,
             'created_by' => User::factory()->state(['role' => 'teacher']),
             'is_active' => true,
             'time_limit' => $this->faker->numberBetween(5, 60)
         ];
     }
 }
 
