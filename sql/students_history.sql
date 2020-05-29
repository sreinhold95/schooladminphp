CREATE TABLE if not exists bsznsql2.students_history LIKE bsznsql2.students;

/*ALTER TABLE bsznsql2.students_history MODIFY COLUMN idstudents int(11) NOT NULL, 
   DROP PRIMARY KEY, ENGINE = MyISAM, ADD action VARCHAR(8) DEFAULT 'insert' FIRST, 
   ADD revision INT(6) NOT NULL AUTO_INCREMENT AFTER action,
   ADD dt_datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER revision,
   ADD PRIMARY KEY (idstudents, revision);*/
   
/*DROP TRIGGER IF EXISTS bsznsql2.students__ai;*/
DROP TRIGGER IF EXISTS bsznsql2.students__au;
DROP TRIGGER IF EXISTS bsznsql2.students__bd;

/*CREATE TRIGGER bsznsql2.students__ai AFTER INSERT ON bsznsql2.students FOR EACH ROW
    INSERT INTO bsznsql2.students_history SELECT 'insert', NULL, NOW(), d.* 
    FROM bsznsql2.students AS d WHERE d.idstudents = NEW.idstudents;*/

CREATE TRIGGER bsznsql2.students__au AFTER UPDATE ON bsznsql2.students FOR EACH ROW
    INSERT INTO bsznsql2.students_history SELECT 'update', NULL, NOW(), d.*
    FROM bsznsql2.students AS d WHERE d.idstudents = NEW.idstudents;

CREATE TRIGGER bsznsql2.students__bd BEFORE DELETE ON bsznsql2.students FOR EACH ROW
    INSERT INTO bsznsql2.students_history SELECT 'delete', NULL, NOW(), d.* 
    FROM bsznsql2.students AS d WHERE d.idstudents = OLD.idstudents;