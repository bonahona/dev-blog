create table LocalUser(
  Id int not null primary key auto_increment,
  ShellUserId int not null,
  key(ShellUserId)
);