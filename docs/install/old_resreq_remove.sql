CREATE DEFINER=`admin`@`localhost` EVENT `Remove old password resets` ON SCHEDULE EVERY 1 MONTH STARTS '2020-05-04 00:01:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM pass_resets WHERE date < NOW() - INTERVAL 30 DAY