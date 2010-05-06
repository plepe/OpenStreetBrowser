#include <unistd.h>

#define CMD "/etc/init.d/apache2"
#define PARAM "restart"

void
main() {
  execl(CMD, CMD, PARAM, (char*)0);
}
