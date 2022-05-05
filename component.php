<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

// !!!!!!! Расскоментируй - если хочешь отправлять штатным почтовым событием FEEDBACK_FORM
// use Bitrix\Main\Mail\Event;

if (CModule::IncludeModule("iblock"))

    $arResult[] = array(
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'PROPERTY_IDS' => $arParams['PROPERTY_CODES'],
    );

$arResult['PROPERTY_DATAS'] = array();
$properties = CIBlockProperty::GetList(
    Array("name" => "asc"),
    Array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams['IBLOCK_ID'],)
);
while ($prop_fields = $properties->GetNext()) {
    array_push($arResult['PROPERTY_DATAS'], $prop_fields);
}

// POST формы
$arResult['ERROR'] = array();
if ((!empty($_REQUEST['NAME'])) && (!empty($_REQUEST['sessid'])) && (empty($_REQUEST['USER']))) {

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $el = new CIBlockElement;
    $section_id = false;
    $sendFields = array();
    foreach ($arResult['PROPERTY_DATAS'] as $sendProps) {
        $sendFields[$sendProps['CODE']] = strip_tags($_POST[$sendProps['CODE']]);
    }
    $fields = array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "PROPERTY_VALUES" => $sendFields,
        "NAME" => strip_tags($_REQUEST['NAME']),
    );

    if ($ID = $el->Add($fields)) {
        array_push($arResult['ERROR'], "NOT_ERROR");
        $arResult["OK_MESSAGE"] = $arParams['OK_TEXT'];

// !!!!!!! Расскоментируй - если хочешь отправлять штатным почтовым событием FEEDBACK_FORM
//        Event::send(array(
//            "EVENT_NAME" => "FEEDBACK_FORM",
//            "LID" => "s1",
//            "C_FIELDS" => array(
//                "EMAIL_TO" => $arParams['MAIL_TO'],
//                "PROPERTY_VALUES" => implode('<br>',$sendFields),
//                "TEXT" => $_REQUEST['NAME'],
//            ),
//        ));

        $toMail = $arParams['MAIL_TO'];
        $fromMail = $arParams['MAIL_FROM'];
        $subjectMail = $_REQUEST['NAME'];
        $messageMail = implode(',',$sendFields);
        if (mail($toMail, $subjectMail, $messageMail, $fromMail))
            echo "Mail sended";
        else
            echo "Mail not sended, check php-mail";

    }
} else {
    // Бот антикапча
    // Капча будет позже, пока проходит тупая проверка- на пустое поле USER
    // Совсем тупенькие боты - будут пытаться его заполнить
}

$this->includeComponentTemplate();
?>


