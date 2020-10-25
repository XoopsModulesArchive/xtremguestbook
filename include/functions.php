<?php
function get_user_data($uid)
{
    if (!(int)$uid) {
        return false;
    }

    $poster = new XoopsUser($uid);

    if ($poster->isActive()) {
        $a_poster['poster'] = "<a href='../../userinfo.php?uid=$uid'>" . $poster->uname() . '</a>';

        $a_poster['active'] = $poster->isActive();

        $a_poster['online'] = $poster->isOnline();

        $a_poster['regdate'] = $poster->user_regdate();

        $a_poster['from'] = $poster->user_from();

        $a_poster['posts'] = $poster->posts();

        $rank = $poster->rank();

        if ($rank['title']) {
            $a_poster['rank'] = $rank['title'];
        }

        if ($rank['image']) {
            $a_poster['rank_img'] = "<img src='" . XOOPS_URL . '/uploads/' . $rank['image'] . "' alt=''>";
        }

        if ($poster->user_avatar()) {
            $a_poster['avatar'] = "<img src='" . XOOPS_URL . '/uploads/' . $poster->user_avatar() . "' alt=''>";
        }

        if ($poster->url()) {
            $a_poster['url'] = "<a href='" . $poster->url() . "' target='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' alt='" . _VISITWEBSITE . "'></a>";
        }

        if ($poster->user_viewemail() && $poster->email()) {
            $a_poster['email'] = "<a href='mailto:" . $poster->email() . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' alt='" . sprintf(_SENDEMAILTO, $poster->uname()) . "'></a>";
        }

        return $a_poster;
    }
  

    return false;
}

function xoopsCodeTareaRet($textarea_id, $cols = 60, $rows = 15, $suffix = null)
{
    $ret = '';

    $hiddentext = isset($suffix) ? 'xoopsHiddenText' . trim($suffix) : 'xoopsHiddenText';

    $ret .= "<a href='javascript:xoopsCodeUrl(\"$textarea_id\");'><img src='" . XOOPS_URL . "/images/url.gif' alt='ÑÇÈØ'></a>&nbsp;<a href='javascript:xoopsCodeEmail(\"$textarea_id\");'><img src='" . XOOPS_URL . "/images/email.gif' alt='ÈÑíÏ'></a>&nbsp;<a href='javascript:xoopsCodeImg(\"$textarea_id\");'><img src='" . XOOPS_URL . "/images/imgsrc.gif' alt='ÕæÑÉ'></a>&nbsp;<a href='javascript:openWithSelfMain(\"" . XOOPS_URL . '/imagemanager.php?target=' . $textarea_id . "\",\"imgmanager\",400,430);'><img src='" . XOOPS_URL . "/images/image.gif' alt='ÅÏÇÑÉ ÇáÕæÑ'></a>&nbsp;<a href='javascript:xoopsCodeCode(\"$textarea_id\");'><img src='" . XOOPS_URL . "/images/code.gif' alt='ÔÝÑÉ'></a>&nbsp;<a href='javascript:xoopsCodeQuote(\"$textarea_id\");'><img src='" . XOOPS_URL . "/images/quote.gif' alt='ÅÞÊÈÇÓ'></a><br>\n";

    $sizearray = ['xx-small', 'x-small', 'small', 'medium', 'large', 'x-large', 'xx-large'];

    $ret .= "<select id='" . $textarea_id . "Size' onchange='setVisible(\"xoopsHiddenText\");setElementSize(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $ret .= "<option value='SIZE'>" . _SIZE . "</option>\n";

    foreach ($sizearray as $size) {
        $ret .= "<option value='$size'>$size</option>\n";
    }

    $ret .= "</select>\n";

    $fontarray = ['Arial', 'Courier', 'Georgia', 'Helvetica', 'Impact', 'Verdana'];

    $ret .= "<select id='" . $textarea_id . "Font' onchange='setVisible(\"xoopsHiddenText\");setElementFont(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $ret .= "<option value='FONT'>" . _FONT . "</option>\n";

    foreach ($fontarray as $font) {
        $ret .= "<option value='$font'>$font</option>\n";
    }

    $ret .= "</select>\n";

    $colorarray = ['00', '33', '66', '99', 'CC', 'FF'];

    $ret .= "<select id='" . $textarea_id . "Color' onchange='setVisible(\"xoopsHiddenText\");setElementColor(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $ret .= "<option value='COLOR'>" . _COLOR . "</option>\n";

    foreach ($colorarray as $color1) {
        foreach ($colorarray as $color2) {
            foreach ($colorarray as $color3) {
                $ret .= "<option value='" . $color1 . $color2 . $color3 . "' style='background-color:#" . $color1 . $color2 . $color3 . ';color:#' . $color1 . $color2 . $color3 . ";'>#" . $color1 . $color2 . $color3 . "</option>\n";
            }
        }
    }

    $ret .= "</select><span id='" . $hiddentext . "'> " . _EXAMPLE . "</span>\n";

    $ret .= "<br>\n";

    $ret .= "<a href='javascript:setVisible(\"" . $hiddentext . '");makeBold("' . $hiddentext . "\");'><img src='" . XOOPS_URL . "/images/bold.gif' alt='ÛÇãÞ'></a>&nbsp;<a href='javascript:setVisible(\"" . $hiddentext . '");makeItalic("' . $hiddentext . "\");'><img src='" . XOOPS_URL . "/images/italic.gif' alt='ãÇÆá'></a>&nbsp;<a href='javascript:setVisible(\"" . $hiddentext . '");makeUnderline("' . $hiddentext . "\");'><img src='" . XOOPS_URL . "/images/underline.gif' alt='ÊÍÊå ÎØ'></a>&nbsp;<a href='javascript:setVisible(\"" . $hiddentext . '");makeLineThrough("' . $hiddentext . "\");'><img src='" . XOOPS_URL . "/images/linethrough.gif' alt='íÍÊæí Úáì ÎØ'></a>&nbsp;<input type='text' id='" . $textarea_id . "Addtext' size='20'>&nbsp;<input type='button' onclick='xoopsCodeText(\"$textarea_id\", \"" . $hiddentext . "\")' value='" . _ADD . "'><br><br><textarea id='" . $textarea_id . "' name='" . $textarea_id . "' cols='$cols' rows='$rows'>" . $GLOBALS[$textarea_id] . "</textarea><br>\n";

    return $ret;
}

/*
*  Displays smilie image buttons used to insert smilie codes to a target textarea in a form
* $textarea_id is a unique of the target textarea
*/
function xoopsSmiliesRet($textarea_id)
{
    $ret = '';

    $myts = MyTextSanitizer::getInstance();

    $smiles = $myts->getSmileys();

    if (empty($smileys)) {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if ($result = $db->query('SELECT * FROM ' . $db->prefix('smiles') . ' WHERE display=1')) {
            while (false !== ($smiles = $db->fetchArray($result))) {
                $ret .= "<a href='javascript: justReturn()' onclick='xoopsCodeSmilie(\"" . $textarea_id . '", " ' . $smiles['code'] . " \");'>";

                $ret .= '<img src="' . XOOPS_URL . '/uploads/' . htmlspecialchars($smiles['smile_url'], ENT_QUOTES | ENT_HTML5) . '" border="0" alt=""></a>';
            }
        }
    } else {
        $count = count($smiles);

        for ($i = 0; $i < $count; $i++) {
            if (1 == $smiles[$i]['display']) {
                $ret .= "<a href='javascript: justReturn()' onclick='xoopsCodeSmilie(\"" . $textarea_id . '", " ' . $smiles[$i]['code'] . " \");'><img src='" . XOOPS_URL . '/uploads/' . htmlspecialchars($smiles['smile_url'], ENT_QUOTES | ENT_HTML5) . "' border='0' alt=''></a>";
            }
        }
    }

    $ret .= "&nbsp;[<a href='javascript:openWithSelfMain(\"" . XOOPS_URL . '/misc.php?action=showpopups&amp;type=smilies&amp;target=' . $textarea_id . "\",\"smilies\",300,475);'>" . _MORE . '</a>]';

    return $ret;
}
