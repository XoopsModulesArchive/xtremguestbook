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
require_once 'admin_header.php';

/*********************************************************/
/* Ephemerids Functions to have a Historic Ephemerids    */
/*********************************************************/

function Choice()
{
    global $xoopsModule;

    xoops_cp_header();

    OpenTable();

    echo "<a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid') . "'>" . _XTG_GUESTBOOK_CONFIG . '</a><br>';

    echo "<a href='index.php?op=Messageshow'>" . _XTG_GUESTBOOK_EDIT . '</a><br>';

    CloseTable();

    xoops_cp_footer();
}

function Messagedel($idmsg)
{
    xoops_cp_header();

    OpenTable();

    echo "<div align='center'>" . _XTG_DEL . "<br><br>
	<input type='button' onclick=\"document.location='index.php?op=Messagedel1&idmsg=" . $idmsg . "'\" value='" . _YES . "'> 
	<input type='button' onclick=\"document.location='index.php'\" value='" . _NO . "'></div>";

    CloseTable();

    xoops_cp_footer();
}

function Messagedel1($idmsg)
{
    global $xoopsDB;

    $result = $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtremguestbook') . " WHERE xtremguestbook_id=$idmsg");

    redirect_header('index.php', 1, _XTG_MSGDEL);

    exit();
}

function Messageapprove($idmsg)
{
    global $xoopsDB;

    $result = $xoopsDB->queryF('update ' . $xoopsDB->prefix('xtremguestbook') . " SET moderate=0 WHERE xtremguestbook_id=$idmsg");

    redirect_header('index.php', 1, _XTG_MSGAPPROVE);

    exit();
}

function Messagesave($idmsg, $uname, $url, $email, $title, $message, $note)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $uname = $myts->addSlashes($uname);

    $email = $myts->addSlashes($email);

    $url = $myts->addSlashes($url);

    $title = $myts->addSlashes($title);

    $message = $myts->addSlashes($message);

    $note = $myts->addSlashes($note);

    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('xtremguestbook') . " set uname='$uname', url='$url', email='$email', title='$title', message='$message', note='$note' WHERE xtremguestbook_id=$idmsg");

    redirect_header('index.php', 1, _XTG_MSGMOD);

    exit();
}

function Messageedit($idmsg)
{
    global $xoopsDB, $xoopsModule;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    $result = $xoopsDB->query('SELECT user_id,uname,url,email,title,message,note FROM ' . $xoopsDB->prefix('xtremguestbook') . " WHERE xtremguestbook_id=$idmsg");

    [$user_id, $uname, $url, $email, $title, $message, $note] = $xoopsDB->fetchRow($result);

    $uname = htmlspecialchars($uname, ENT_QUOTES | ENT_HTML5);

    $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5);

    $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5);

    $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5);

    OpenTable();

    if (0 != $user_id) {
        $disabled = "readonly='readonly'";
    }

    echo "<form name='guestbook' action='index.php?op=Messagesave' method='post'>
			<table width='95%' border='0'>
			<tr><td>
			" . _XTG_NAME . "
			</td><td>
			<input name='uname' type='text' " . $disabled . " value='" . $uname . "'>
			</td></tr>
			<tr><td>
			" . _XTG_EMAIL . "
			</td><td>
			<input name='email' type='text' " . $disabled . " value='" . $email . "'>
			</td></tr>
			<tr><td>
			" . _XTG_URL . "
			</td><td>
			<input name='url' type='text' " . $disabled . " value='" . $url . "'>
			</td></tr>
			<tr><td>
			" . _XTG_TITLE . "
			</td><td>
			<input name='title' id='title' value='" . $title . "' type='text' >
			</td></tr>
			<tr><td>
			" . _XTG_MESSAGE . "
			</td><td>
			<textarea name='message' id='message' cols='50' rows='5'>$message</textarea>
			</td></tr>
			<tr><td>
			" . _XTG_NOTE . "
			</td><td>
			<textarea name='note' id='note' cols='50' rows='5'>$note</textarea>
			</td></tr>
			<tr><td>
			&nbsp;
			</td><td>
			<input type='hidden' name='idmsg' value='$idmsg'>
    	<input type='submit' value='" . _XTG_SEND . "'>
			</td></tr>
			</table>
    	</form>";

    CloseTable();

    xoops_cp_footer();
}

function Messageshow()
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    OpenTable();

    echo "<table border='1' width='90%'>
			<tr><td><b>" . _XTG_NUM . '</b></td><td><b>' . _XTG_NAME . '</b></td><td><b>' . _XTG_TITLE . '</b></td><td><b>' . _XTG_MESSAGE . '</b></td><td><b>' . _XTG_ACTION . '</b></td></tr>';

    $result = $xoopsDB->query('SELECT xtremguestbook_id,title,message,uname,user_id,moderate FROM ' . $xoopsDB->prefix('xtremguestbook') . ' order by post_time desc');

    $nbmessage = $xoopsDB->getRowsNum($result);

    while (list($idmsg, $title, $message, $uname, $uid, $moderate) = $xoopsDB->fetchRow($result)) {
        $message = $myts->displayTarea($message, 0, 1, 1);

        $title = $myts->displayTarea($title, 0, 0, 0);

        if ($moderate) {
            $approve = "<a href='index.php?op=Messageapprove&idmsg=$idmsg'>" . _XTG_APPROVE . '</a> | ';
        } else {
            $approve = '';
        }

        echo "<tr>
	    	<td>$nbmessage )</td>
	    	<td>";

        if (0 != $uid) {
            echo "<a href='../../../userinfo.php?uid=$uid'>$uname</a>";
        } else {
            echo $uname;
        }

        echo "</td>
	    	<td>$title&nbsp;</td>
	    	<td>$message&nbsp;</td>
	    	<td>" . $approve . "<a href='index.php?op=Messageedit&idmsg=$idmsg'>" . _XTG_EDIT . "</a> | <a href='index.php?op=Messagedel&idmsg=$idmsg'>" . _XTG_DEL . '</a></td>
	    	</tr>';

        $nbmessage--;
    }

    echo '</table>';

    CloseTable();

    xoops_cp_footer();
}

if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'Messagesave':
        Messagesave($idmsg, $_POST['uname'], $_POST['url'], $_POST['email'], $_POST['title'], $_POST['message'], $_POST['note']);
        break;
    case 'Messageedit':
        Messageedit($_GET['idmsg']);
        break;
    case 'Messageapprove':
        Messageapprove($_GET['idmsg']);
        break;
    case 'Messagedel':
        Messagedel($_GET['idmsg']);
        break;
    case 'Messagedel1':
        Messagedel1($_GET['idmsg']);
        break;
    case 'Messageshow':
        Messageshow();
        break;
    default:
        Choice();
        break;
}
