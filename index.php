<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       < http://xoops.eti.br >                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

include 'header.php';
include 'include/functions.php';

$GLOBALS['xoopsOption']['template_main'] = 'xtremguestbook_index.html';

if ('xtremguestbook' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();
} else {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';
}

require XOOPS_ROOT_PATH . '/class/pagenav.php';

$nbmsgaffich = 0;

//count number of messages
$sqlquery = $xoopsDB->query('SELECT count(*) as nbmsg from ' . $xoopsDB->prefix('xtremguestbook') . ' where moderate=0');
$sqlfetch = $xoopsDB->fetchArray($sqlquery);
$nbmessage = $sqlfetch['nbmsg'];

//Admin or not
if ($xoopsUser) {
    $adminview = $xoopsUser->isAdmin($xoopsModule->mid());
} else {
    $adminview = 0;
}

if (!isset($limite)) {
    $limite = 0;
}

$pagenav = new XoopsPageNav($nbmessage, $xoopsModuleConfig['nbmsgbypage'], $limite, 'limite', '');

$xoopsTpl->assign('gb_title', _XTG_NAMEMODULE);
$xoopsTpl->assign('gb_sign', "<a href='sign.php'>" . _XTG_SIGNGUESTBOOK . '</a>');
$xoopsTpl->assign('gb_message_count', sprintf(_XTG_THEREIS, '<b>' . $nbmessage . '</b>'));

if ($adminview && $xoopsModuleConfig['moderate']) {
    $sqlquerymoderate = $xoopsDB->query('SELECT count(*) as nbmsg from ' . $xoopsDB->prefix('xtremguestbook') . ' where moderate=1');

    $sqlfetchmoderate = $xoopsDB->fetchArray($sqlquerymoderate);

    $nbmoderating = $sqlfetchmoderate['nbmsg'];

    $xoopsTpl->assign('gb_moderated', true);

    $xoopsTpl->assign('gb_moderate_text', sprintf(_XTG_MODERATING, "<font class='fg2'><a href='admin/index.php?op=Messageshow'>" . $nbmoderating . '</a></font>'));
}

$xoopsTpl->assign('gb_page_nav', $pagenav->renderNav());

//Select messages
$sqlquery = $xoopsDB->query('SELECT user_id,uname,url,email,title,message,note,post_time,poster_ip,xtremguestbook_id from ' . $xoopsDB->prefix('xtremguestbook') . ' where moderate=0 order by post_time desc limit ' . (int)$limite . ',' . $xoopsModuleConfig['nbmsgbypage']);

//Admin or not
if ($xoopsUser) {
    $adminview = $xoopsUser->isAdmin();
} else {
    $adminview = 0;
}

$nbmessage -= $limite;
while (false !== ($sqlfetch = $xoopsDB->fetchArray($sqlquery))) {
    $myts = MyTextSanitizer::getInstance();

    $user_id = $myts->displayTarea($sqlfetch['user_id']);

    $note = $myts->displayTarea($sqlfetch['note']);

    $xtremguestbook_id = $myts->displayTarea($sqlfetch['xtremguestbook_id']);

    if ($poster = get_user_data($user_id)) {
        $a_msg = &$poster;

        $a_msg['is_user'] = true;
    } else {
        $a_msg = [];

        $a_msg['is_user'] = false;

        $a_msg['poster'] = $myts->displayTarea($sqlfetch['uname']);

        if ($sqlfetch['url']) {
            $a_msg['url'] = "<a href='" . $myts->displayTarea($sqlfetch['url']) . "' target='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' alt='" . _VISITWEBSITE . "'></a>";
        }

        if ($sqlfetch['email']) {
            $a_msg['email'] = "<a href='mailto:" . $myts->displayTarea($sqlfetch['email']) . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' alt='" . sprintf(_SENDEMAILTO, $a_msg['poster']) . "'></a>";
        }
    }

    $a_msg['i'] = $nbmessage--;

    $a_msg['title'] = $myts->displayTarea($sqlfetch['title'], 0, 0, 0);

    $a_msg['date'] = formatTimestamp($sqlfetch['post_time'], 'm');

    $a_msg['msg'] = $myts->displayTarea($sqlfetch['message'], $xoopsModuleConfig['allowhtml'], $xoopsModuleConfig['allowsmileys'], $xoopsModuleConfig['allowbbcode']);

    if ($adminview) {
        $a_msg['admin'] = "<img src='" . XOOPS_URL . "/images/icons/ip.gif' alt='" . $sqlfetch['poster_ip'] . "'>
		<a href='admin/index.php?op=Messageedit&idmsg=$xtremguestbook_id'><img src='" . XOOPS_URL . "/images/icons/edit.gif' alt='" . _XTG_MODIFYPOST . "' border='0'></a>
		<a href='admin/index.php?op=Messagedel&idmsg=$xtremguestbook_id'><img src='" . XOOPS_URL . "/images/icons/delete.gif' alt='" . _XTG_DELETEPOST . "' border='0'></a>";
    }

    if ($note) {
        $a_msg['note_title'] = _XTG_NOTE;

        $a_msg['note_msg'] = $note;
    }

    $nbmsgaffich++;

    $xoopsTpl->append('gb_posts', $a_msg);
}
$xoopsTpl->assign('gb_copyright', _XTG_COPYRIGHT);

require XOOPS_ROOT_PATH . '/footer.php';
