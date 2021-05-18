drop table if exists users;

drop table if exists challenges;

create table users (id int not null, username string, password string, primary key (id));

insert into users values (1, "admin", "be49db543011ca91169903c1a3ca2c23");

CREATE TABLE challenges (id int PRIMARY KEY, title string NOT NULL, description string NOT NULL);

insert into challenges values (1, "Quantum Cryptography", "Design a post-quantum secure Cryptographic Hashing Algorithm - 10 points");
insert into challenges values (2, "Travelling Penetration Tester", "Design an algorithm that traverses a network in the smallest number of hops while pwning each host only once - 15 points");
insert into challenges values (3, "Stuxnet Malware Fingerprinting", "Identify the source of the stuxnet malware - 9 points");
insert into challenges values (4, "Linux Kernel Code Audit", "Review all Minnesota University Kernel Commits for Vulnerabilities - 12 points");