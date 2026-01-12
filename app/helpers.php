<?php

const FIRST_QUARTER = [1,2,3];
const SECOND_QUARTER = [4,5,6];
const THIRD_QUARTER = [7,8,9];
const FOURTH_QUARTER = [10,11,12];
function format_phone_number($mynum, $mask)
{
    /*********************************************************************/
    /*   Purpose: Return either masked phone number or false             */
    /*     Masks: Val=1 or xxx xxx xxxx                                             */
    /*            Val=2 or xxx xxx.xxxx                                             */
    /*            Val=3 or xxx.xxx.xxxx                                             */
    /*            Val=4 or (xxx) xxx xxxx                                           */
    /*            Val=5 or (xxx) xxx.xxxx                                           */
    /*            Val=6 or (xxx).xxx.xxxx                                           */
    /*            Val=7 or (xxx) xxx-xxxx                                           */
    /*            Val=8 or (xxx)-xxx-xxxx                                           */
    /*********************************************************************/
    $val_num        = validate_phone_number($mynum);
    if (!$val_num && !is_string($mynum)) {
        echo "Number $mynum is not a valid phone number! \n";
        return false;
    }   // end if !$val_num
    if (($mask == 1) || ($mask == 'xxx xxx xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '$1 $2 $3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 1
    if (($mask == 2) || ($mask == 'xxx xxx.xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '$1 $2.$3'." \n",
            $mynum
        );
        return $phone;
    }   // end if $mask == 2
    if (($mask == 3) || ($mask == 'xxx.xxx.xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '$1.$2.$3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 3
    if (($mask == 4) || ($mask == '(xxx) xxx xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '($1) $2 $3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 4
    if (($mask == 5) || ($mask == '(xxx) xxx.xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '($1) $2.$3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 5
    if (($mask == 6) || ($mask == '(xxx).xxx.xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '($1).$2.$3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 6
    if (($mask == 7) || ($mask == '(xxx) xxx-xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '($1) $2-$3'." ",
            $mynum
        );
        return $phone;
    }   // end if $mask == 7
    if (($mask == 8) || ($mask == '(xxx)-xxx-xxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '($1)-$2-$3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 8
    if (($mask == 9) || ($mask == 'xxxxxxxxxx')) {
        $phone = preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '$1$2$3'."",
            $mynum
        );
        return $phone;
    }   // end if $mask == 9
    return false;       // Returns false if no conditions meet or input
}  // end function format_phone_number

function validate_phone_number($phone)
{
    /*********************************************************************/
    /*   Purpose:   To determine if the passed string is a valid phone  */
    /*              number following one of the establish formatting        */
    /*                  styles for phone numbers.  This function also breaks    */
    /*                  a valid number into it's respective components of:      */
    /*                          3-digit area code,                                      */
    /*                          3-digit exchange code,                                  */
    /*                          4-digit subscriber number                               */
    /*                  and validates the number against 10 digit US NANPA  */
    /*                  guidelines.                                                         */
    /*********************************************************************/
    $format_pattern =   '/^(?:(?:\((?=\d{3}\)))?(\d{3})(?:(?<=\(\d{3})\))'.
        '?[\s.\/-]?)?(\d{3})[\s\.\/-]?(\d{4})\s?(?:(?:(?:'.
        '(?:e|x|ex|ext)\.?\:?|extension\:?)\s?)(?=\d+)'.
        '(\d+))?$/';
    $nanpa_pattern      =   '/^(?:1)?(?(?!(37|96))[2-9][0-8][0-9](?<!(11)))?'.
        '[2-9][0-9]{2}(?<!(11))[0-9]{4}(?<!(555(01([0-9]'.
        '[0-9])|1212)))$/';

    // Init array of variables to false
    $valid = array('format' =>  false,
        'nanpa' => false,
        'ext'       => false,
        'all'       => false);

    //Check data against the format analyzer
    if (preg_match($format_pattern, $phone, $matchset)) {
        $valid['format'] = true;
    }

    //If formatted properly, continue
    //if($valid['format']) {
    if (!$valid['format']) {
        return false;
    } else {
        //Set array of new components
        $components =   array( 'ac' => $matchset[1], //area code
            'xc' => $matchset[2], //exchange code
            'sn' => $matchset[3] //subscriber number
        );
        //              $components =   array ( 'ac' => $matchset[1], //area code
        //                                              'xc' => $matchset[2], //exchange code
        //                                              'sn' => $matchset[3], //subscriber number
        //                                              'xn' => $matchset[4] //extension number
        //                                              );

        //Set array of number variants
        $numbers    =   array( 'original' => $matchset[0],
            'stripped' => substr(preg_replace('[\D]', '', $matchset[0]), 0, 10)
        );

        //Now let's check the first ten digits against NANPA standards
        if (preg_match($nanpa_pattern, $numbers['stripped'])) {
            $valid['nanpa'] = true;
        }

        //If the NANPA guidelines have been met, continue
        if ($valid['nanpa']) {
            if (!empty($components['xn'])) {
                if (preg_match('/^[\d]{1,6}$/', $components['xn'])) {
                    $valid['ext'] = true;
                }   // end if if preg_match
            } else {
                $valid['ext'] = true;
            }   // end if if  !empty
        }   // end if $valid nanpa

        //If the extension number is valid or non-existent, continue
        if ($valid['ext']) {
            $valid['all'] = true;
        }   // end if $valid ext
    }   // end if $valid
    return $valid['all'];
}

function clean_string($value)
{
    if (blank($value))
    {
        return null;
    }

    return \Illuminate\Support\Str::of($value)->trim()->value();
}

function checked($value)
{
    return $value ? 'checked' : '';
}

function pass_fail($value)
{
    return $value ? 'Pass' : 'Fail';
}

function true_false($value)
{
    return $value ? 'true' : 'false';
}

function is_boolean($value)
{
    return $value ? '1' : '0';
}

function yn($value)
{
    return $value ? 'Y' : 'N';
}

function active($value)
{
    return $value ? 'Active' : 'Archived';
}

function archived($value)
{
    return $value ? 'Archived' : 'Active';
}

function valid_email($email)
{
    return !!filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function properize($string)
{
    return $string.(preg_match('/[s]$/', $string) ? "'" : "'s");
}

function dateForHumans(\Carbon\Carbon $date)
{
    return $date->format('m/d/Y');
}

function phone($value)
{
    if ($value) {
//        if (Str::length($value) != 10) {
//            return '';
//        }
        $sanitized = preg_replace('~\D~', '', $value);

        $phone = format_phone_number($sanitized, 7);
        if ($phone === "Number is not a valid phone number!") {
            return '';
        } else {
            return $phone;
        }
    } else {
        return '';
    }
}

function carbon($value)
{
    return \Carbon\Carbon::parse($value);
}

function money($value)
{
    //return strval('$' . number_format($value,2));
    return getFormattedNumber($value, 'en_US', NumberFormatter::CURRENCY);
}

function getFormattedNumber(
    $value,
    $locale = 'en_US',
    $style = NumberFormatter::DECIMAL,
    $precision = 2,
    $groupingUsed = true,
    $currencyCode = 'USD'
) {
    if ($value != null)
    {
        $formatter = new NumberFormatter($locale, $style);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, $groupingUsed);
        if ($style == NumberFormatter::CURRENCY) {
            $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);
        }
        return $formatter->format($value);
    } else {
        return '$0.00';
    }

}

function card_exp_year_options()
{
    $year = \Carbon\CarbonImmutable::now()->year;
    $options = [];
    for ($i=0;$i<10;$i++) {
        array_push($options, $year + $i);
    }

    return $options;
}

function card_exp_month_options()
{
    $options = ['01','02','03','04','05','06','07','08','09','10','11','12'];

    return $options;
}

