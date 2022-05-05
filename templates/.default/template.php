<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);
?>
<div class="custom_send_form">
    <? if (!empty($arResult['ERROR'])) { ?>
        <div class="succes_send_form">
            <? echo $arResult["OK_MESSAGE"]; ?>
        </div>
    <? } ?>
    <form name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
        <?= bitrix_sessid_post() ?>
        <div class="send_form_field">
            <strong>Как к Вам обращаться?</strong>
            <input type="text" name="NAME" maxlength="255" value="" required>
        </div>
        <div class="send_form_field send_form_field_d">
            <input type="text" name="USER" value="">
        </div>
        <? foreach ($arResult['PROPERTY_DATAS'] as $field) { ?>
            <div class="send_form_field">
                <strong><? echo $field['NAME'] ?>:</strong>
                <? if ($field['PROPERTY_TYPE'] == "F") { ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.file.input", "dragn_n_drop", Array(
                        "INPUT_NAME" => $field["CODE"],
                        "MULTIPLE" => "Y",
                        "MODULE_ID" => "main",
                        "MAX_FILE_SIZE" => "",
                        "ALLOW_UPLOAD" => "A",
                        "ALLOW_UPLOAD_EXT" => ""
                    ),
                        false
                    ); ?>
                <? } elseif ($field['DEFAULT_VALUE']['TYPE'] == "HTML") { ?>
                    <textarea name="<? echo $field['CODE'] ?>" cols="30" rows="10"></textarea>
                <? } else { ?>
                    <input type="text" name="<? echo $field['CODE'] ?>" maxlength="255" value="">
                <? } ?>

                <? //echo $field['HINT'] ?>
            </div>
        <? } ?>
        <div class="send_form_submit">
            <input type="submit" value="Отправить">
        </div>
    </form>
</div>
