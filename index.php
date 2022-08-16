<?php

// Заданный массив
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Разбиение и объединение ФИО
function getPartsFromFullname($fullName){
    $person_name=['surname', 'name', 'patronomyc'];
    return array_combine($person_name,explode(' ', $fullName));
}

function getFullnameFromParts($surname, $name, $potronomyc){
    return $surname .= ' ' . $name . ' ' . $potronomyc;
}

// Сокращение ФИО
function getShortName($fullName){
    $parts = getPartsFromFullname($fullName);
    return $parts['name'] . ' ' . mb_substr($parts['surname'], 0, 1) . '.';
}

// Функция определения пола по ФИО
function getGenderFromName($fullName){
    $parts = getPartsFromFullname($fullName);
    $genderFlag = 0;
    if(mb_substr($parts['patronomyc'], -3, 3) == 'вна')
        --$genderFlag;

    if(mb_substr($parts['patronomyc'], -2, 2) == 'ич')
        ++$genderFlag;

    if(mb_substr($parts['name'], -1, 1) == 'а')
        --$genderFlag;

    if(mb_substr($parts['name'], -1, 1) == 'й' || mb_substr($parts['name'], -1, 1) == 'н')
        ++$genderFlag;

    if(mb_substr($parts['surname'], -2, 2) == 'ва')
        --$genderFlag;

    if(mb_substr($parts['surname'], -1, 1) == 'в')
        ++$genderFlag;

    switch($genderFlag <=> 0){
        case 1:
            return 'male';
            break;
        case -1:
            return 'female';
            break;
        default:
            return'undefined';
    }
}

// Определение возрастно-полового состава
function getGenderDescription($personArray){
    $males = array_filter($personArray, function($person){
        return getGenderFromName($person['fullname']) == 'male';
    });

    $females = array_filter($personArray, function($person){
        return getGenderFromName($person['fullname']) == 'female';
    });
    
    $arrUndefined = array_filter($personArray, function($person){
        return getGenderFromName($person['fullname']) == 'undefined';
    });
    
    $male = round(count($males)*100/count($personArray), 1);
    $female = round(count($females)*100/count($personArray), 1);
    $undefined = round(count($arrUndefined)*100/count($personArray), 1);

    echo <<<HEREDOCLETTER
    Гендерный состав аудитории:\n
    ---------------------------\n
    Мужчины - $male%\n
    Женщины - $female%\n
    Не удалось определить - $undefined%\n

    HEREDOCLETTER;
}

// Идеальный подбор пары
function getPerfectPartner($surname,$name,$potronomyc,$personArray){
   $fullName1 = mb_convert_case(getFullnameFromParts($surname, $name, $potronomyc), MB_CASE_TITLE_SIMPLE);
   $fullName2 = $personArray[rand(0, count($personArray) - 1)]['fullname'];
   for ($i = 1; $i < 1000; $i++){  //цикл для поиска разнополой пары
        if ((getGenderFromName($fullName1) != getGenderFromName($fullName2)) && (getGenderFromName($fullName1) != 'undefined') && (getGenderFromName($fullName2) != 'undefined')) {
            $perfect_percent = number_format(rand(5000, 10000) / 100, 2, '.', ''); //процент "идеальности" с точностью до 2-го знака после запятой
            $name1 = getShortName($fullName1);
            $name2 = getShortName($fullName2);
            echo<<<HEREDOCLETTER
            $name1 + $name2 = 
            ♡ Идеально на $perfect_percent% ♡\n
            HEREDOCLETTER;
            break; // прерывание цикла, если в заданном массиве найден человек противоположного пола
        }     
        $fullName2 = $personArray[rand(0, count($personArray) - 1)]['fullname'];    
     }            
}

// Проверка работы функций

// Определения состава аудитории на заданном массиве
getGenderDescription($example_persons_array);

// Поиск идеальной пары из заданного массива для Петровой Марины Сергеевны
getPerfectPartner('Петрова', 'Марина', 'Сергеевна', $example_persons_array);

// Поиск идеальной пары из заданного массива для Михайлова Контантина Петровича
getPerfectPartner('Михайлов', 'Константин', 'Петрович', $example_persons_array);

?>