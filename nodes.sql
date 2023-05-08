CREATE TABLE nodes (
  id bigint NOT NULL AUTO_INCREMENT,
  parent_id bigint DEFAULT NULL,
  text varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_0900_ai_ci;

ALTER TABLE nodes
ADD CONSTRAINT fk_nodes_parent_id FOREIGN KEY (parent_id)
REFERENCES nodes (id) ON DELETE CASCADE;
