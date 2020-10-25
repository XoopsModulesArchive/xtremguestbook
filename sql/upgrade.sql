ALTER TABLE `xoops_xtremguestbook`
    ADD `note` LONGTEXT AFTER message
ALTER TABLE `xoops_xtremguestbook`
    ADD `moderate` TINYINT(1)
UPDATE xoops_xtremguestbook
   SET moderate=0
