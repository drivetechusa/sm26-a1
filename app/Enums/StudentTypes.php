<?php

namespace App\Enums;

enum StudentTypes : string
{
    case COURSE_A = 'Course A';
    case COURSE_B = 'Course B';
    case COURSE_C = 'Course C';
    case LXL = 'BTW Only';
    case POINT_REDUCTION = 'Point Reduction';
    case INSTRUCTOR_TRAINING = 'Instructor Training';
    case DRIVER_EVALUATION = 'Driver Evaluation';
    case ROAD_TESTING = 'Skills Test Only';
    case PERMIT_TESTING = 'Permit Test Only';
    case LxL = 'LxL';
    case LADS_HAND = 'LADS Hand';
    case DIP = 'DIP';

    public function label()
    {
        return match ($this) {
            static::COURSE_A => 'Course A',
            static::COURSE_B => 'Course B',
            static::COURSE_C => 'Course C',
            static::POINT_REDUCTION => 'Point Reduction',
            static::ROAD_TESTING => 'Road Test Only',
            static::PERMIT_TESTING => 'Permit Test Only',
            static::LXL => 'BTW Only',
            static::LxL => 'LxL',
            static::INSTRUCTOR_TRAINING => 'Instructor Training',
            static::DRIVER_EVALUATION => 'Driver Evaluation',
            static::LADS_HAND => 'LADS Hand',
            static::DIP => 'DIP',

        };
    }

    public function extendedLabel()
    {
        return match ($this) {
            static::COURSE_A => '8 Hour Classroom | 6 Hours In-Car',
            static::COURSE_B => '8 Hour Classroom | 8 Hours In-Car',
            static::COURSE_C => '8 Hour Classroom | 10 Hours In-Car',
            static::POINT_REDUCTION => 'Point Reduction',
            static::ROAD_TESTING => 'Road Test Only',
            static::PERMIT_TESTING => 'Permit Test Only',
            static::LXL => 'Behind the Wheel Lessons',
            static::LxL => 'Behind the Wheel Lessons',
        };
    }

    public function color()
    {
        return match ($this) {
            static::COURSE_A => 'yellow',
            static::COURSE_B => 'green',
            static::COURSE_C => 'orange',
            static::POINT_REDUCTION => 'red',
            static::ROAD_TESTING => 'amber',
            static::PERMIT_TESTING => 'purple',
            static::LXL => 'blue',
            static::LxL => 'blue',
        };
    }
    public function carHours()
    {
        return match ($this) {
            static::COURSE_A => '6',
            static::COURSE_B => '8',
            static::COURSE_C => '10',
            static::POINT_REDUCTION => '0',
            static::ROAD_TESTING => '0',
            static::PERMIT_TESTING => '0',
        };
    }
}
