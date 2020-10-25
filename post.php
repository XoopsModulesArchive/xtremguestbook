<?php
if ('' != $_POST['title']) {
    include 'header.php';

    $myts = MyTextSanitizer::getInstance();

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        $uid = 0;
    }

    $title = $myts->addSlashes($_POST['title']);

    $email = $myts->addSlashes($_POST['email']);

    $url = $myts->addSlashes($_POST['url']);

    if ($xoopsUser) {
        $username = $xoopsUser->uname();
    } else {
        $username = $myts->addSlashes($_POST['username']);
    }

    $message = $myts->addSlashes($_POST['message']);

    $datetime = time();

    $poster_ip = $GLOBALS['REMOTE_ADDR'];

    if ($xoopsUser) {
        $moderate = $xoopsUser->isAdmin($xoopsModule->mid()) ? false : $xoopsModuleConfig['moderate'];
    } else {
        $moderate = $xoopsModuleConfig['moderate'];
    }

    $sqlinsert = 'INSERT INTO ' . $xoopsDB->prefix('xtremguestbook') . ' (user_id,uname,title,message,post_time,email,url,poster_ip,moderate) VALUES (' . $uid . ",'" . $username . "','" . $title . "','" . $message . "','" . $datetime . "','" . $email . "','" . $url . "','" . $poster_ip . "','" . (int)$moderate
                 . "')";

    if (!$result = $xoopsDB->queryF($sqlinsert)) {
        $messagesent = _XTG_ERRORINSERT;
    }

    // RMV-NOTIFY

    // Define tags for notification message

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'new_post', []);

    // Send mail to webmaster

    if ($xoopsModuleConfig['sendmail2webmaster']) {
        $subject = $xoopsConfig['sitename'] . ' - ' . _XTG_NAMEMODULE;

        $xoopsMailer = getMailer();

        $xoopsMailer->useMail();

        $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

        $xoopsMailer->setFromName($xoopsConfig['sitename']);

        $xoopsMailer->setSubject($subject);

        $xoopsMailer->setBody(_XTG_NEWMESSAGE . ' ' . XOOPS_URL . '/modules/xtremguestbook/');

        $xoopsMailer->send();
    }

    $messagesent = '';

    if ($xoopsModuleConfig['moderate']) {
        $messagesent .= '<br>' . _XTG_AFTERMODERATE;
    }

    redirect_header('index.php', 2, $messagesent);
}
