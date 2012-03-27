CREATE TABLE checks (id INT AUTO_INCREMENT, status VARCHAR(45), created DATETIME, project_id INT NOT NULL, INDEX fk_checks_project1_idx (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB;
CREATE TABLE client (id INT AUTO_INCREMENT, title VARCHAR(45), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB;
CREATE TABLE path (id INT AUTO_INCREMENT, template_type VARCHAR(45) NOT NULL, object_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, meta_page_title VARCHAR(255), meta_navigation_title VARCHAR(255), meta_path VARCHAR(255), meta_keywords MEDIUMTEXT, meta_description MEDIUMTEXT, meta_visible_in_navigation TINYINT(1) DEFAULT '1' NOT NULL, root_id BIGINT, lft INT, rgt INT, level SMALLINT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = INNODB;
CREATE TABLE project (id INT AUTO_INCREMENT, domain VARCHAR(255), client_id INT NOT NULL, INDEX fk_project_client_idx (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB;
CREATE TABLE project_has_team_member (project_id INT, team_member_id INT, INDEX fk_project_has_team_member_team_member1_idx (team_member_id), INDEX fk_project_has_team_member_project1_idx (project_id), PRIMARY KEY(project_id, team_member_id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB;
CREATE TABLE team_member (id INT AUTO_INCREMENT, name VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, telephone VARCHAR(45), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB;
CREATE TABLE sf_guard_forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id BIGINT AUTO_INCREMENT, user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255), last_name VARCHAR(255), email_address VARCHAR(255) NOT NULL UNIQUE, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE checks ADD CONSTRAINT checks_project_id_project_id FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE project ADD CONSTRAINT project_client_id_client_id FOREIGN KEY (client_id) REFERENCES client(id) ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE project_has_team_member ADD CONSTRAINT project_has_team_member_team_member_id_team_member_id FOREIGN KEY (team_member_id) REFERENCES team_member(id) ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE project_has_team_member ADD CONSTRAINT project_has_team_member_project_id_project_id FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE sf_guard_forgot_password ADD CONSTRAINT sf_guard_forgot_password_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
