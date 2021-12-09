<?php

if(!function_exists('mysql_urldecode')){
	function mysql_urldecode(){
/*		return '
DROP FUNCTION IF EXISTS urldecode;

DELIMITER |

CREATE FUNCTION urldecode (s VARCHAR(4096)) RETURNS VARCHAR(4096)

DETERMINISTIC
CONTAINS SQL
BEGIN
       DECLARE c VARCHAR(4096) DEFAULT \'\';
       DECLARE pointer INT DEFAULT 1;
       DECLARE h CHAR(2);
       DECLARE h1 CHAR(1);
       DECLARE h2 CHAR(1);
       DECLARE s2 VARCHAR(4096) DEFAULT \'\';

       IF ISNULL(s) THEN
          RETURN NULL;
       ELSE
       SET s2 = \'\';
       WHILE pointer <= LENGTH(s) DO
          SET c = MID(s,pointer,1);
          IF c = \'+\' THEN
             SET c = \' \';
          ELSEIF c = \'%\' AND pointer + 2 <= LENGTH(s) THEN
             SET h1 = LOWER(MID(s,pointer+1,1));
             SET h2 = LOWER(MID(s,pointer+2,1));
             IF (h1 BETWEEN \'0\' AND \'9\' OR h1 BETWEEN \'a\' AND \'f\')
                 AND
                 (h2 BETWEEN \'0\' AND \'9\' OR h2 BETWEEN \'a\' AND \'f\')
                 THEN
                   SET h = CONCAT(h1,h2);
                   SET pointer = pointer + 2;
                   SET c = CHAR(CONV(h,16,10));
              END IF;
          END IF;
          SET s2 = CONCAT(s2,c);
          SET pointer = pointer + 1;
       END while;
       END IF;
       RETURN s2;
END;

|

DELIMITER ;';
*/
return "
CREATE FUNCTION urldecode(s VARCHAR(4096)) RETURNS varchar(4096) CHARSET utf8
    DETERMINISTIC
BEGIN
       DECLARE c VARCHAR(4096) DEFAULT '';
       DECLARE pointer INT DEFAULT 1;
       DECLARE h CHAR(2);
       DECLARE h1 CHAR(1);
       DECLARE h2 CHAR(1);
       DECLARE s2 VARCHAR(4096) DEFAULT '';

       IF ISNULL(s) THEN
          RETURN NULL;
       ELSE
       SET s2 = '';
       WHILE pointer <= LENGTH(s) DO
          SET c = MID(s,pointer,1);
          IF c = '+' THEN
             SET c = ' ';
          ELSEIF c = '%' AND pointer + 2 <= LENGTH(s) THEN
             SET h1 = LOWER(MID(s,pointer+1,1));
             SET h2 = LOWER(MID(s,pointer+2,1));
             IF (h1 BETWEEN '0' AND '9' OR h1 BETWEEN 'a' AND 'f')
                 AND
                 (h2 BETWEEN '0' AND '9' OR h2 BETWEEN 'a' AND 'f')
                 THEN
                   SET h = CONCAT(h1,h2);
                   SET pointer = pointer + 2;
                   SET c = CHAR(CONV(h,16,10));
              END IF;
          END IF;
          SET s2 = CONCAT(s2,c);
          SET pointer = pointer + 1;
       END while;
       END IF;
       RETURN s2;
END
";
	}
}

?>