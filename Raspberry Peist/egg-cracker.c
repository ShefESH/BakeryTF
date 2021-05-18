#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

void main() {
  setuid(0);
  printf("Howdy fella, I can help you crack eggs!\n");
  printf("How shall I scramble things?: ");
  fflush(stdout);
  char perm[50];
  fgets(perm, sizeof(perm), stdin);
  perm[3] = 0;
  char cmd[150];
  sprintf(cmd, "chmod %s .egg", perm);
  system(cmd);
  //printf("Ran: %s\n", cmd);
}
