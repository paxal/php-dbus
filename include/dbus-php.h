#define FFI_SCOPE "PAXAL_DBUS"
#define FFI_LIB "libdbus-1.so"

struct DBusError
{
  const char *name;    /**< public error name field */
  const char *message; /**< public error message field */

  unsigned int dummy1; /**< placeholder */
  unsigned int dummy2; /**< placeholder */
  unsigned int dummy3; /**< placeholder */
  unsigned int dummy4; /**< placeholder */
  unsigned int dummy5; /**< placeholder */

  void *padding1; /**< placeholder */
};

void dbus_error_init(struct DBusError* error);
void        dbus_error_free      (struct DBusError       *error);

typedef struct DBusConnection DBusConnection;

typedef enum
{
  DBUS_BUS_SESSION,    /**< The login session bus */
  DBUS_BUS_SYSTEM,     /**< The systemwide bus */
  DBUS_BUS_STARTER     /**< The bus that started us, if any */
} DBusBusType;

DBusConnection *dbus_bus_get(DBusBusType     type, struct DBusError      *error);

void dbus_bus_add_match(DBusConnection *connection, const char     *rule, struct DBusError      *error);
void  dbus_connection_flush(DBusConnection             *connection);
bool dbus_error_is_set    (const struct DBusError *error);
bool        dbus_connection_read_write                   (struct DBusConnection             *connection,
                                                                 int                         timeout_milliseconds);

typedef struct DBusMessage DBusMessage;
DBusMessage*       dbus_connection_pop_message                  (DBusConnection             *connection);

void          dbus_message_unref            (DBusMessage   *message);


typedef struct DBusMessageIter DBusMessageIter;

/**
 * DBusMessageIter struct; contains no public fields.
 */
struct DBusMessageIter
{
  void *dummy1;         /**< Don't use this */
  void *dummy2;         /**< Don't use this */
  uint32_t dummy3; /**< Don't use this */
  int dummy4;           /**< Don't use this */
  int dummy5;           /**< Don't use this */
  int dummy6;           /**< Don't use this */
  int dummy7;           /**< Don't use this */
  int dummy8;           /**< Don't use this */
  int dummy9;           /**< Don't use this */
  int dummy10;          /**< Don't use this */
  int dummy11;          /**< Don't use this */
  int pad1;             /**< Don't use this */
  void *pad2;           /**< Don't use this */
  void *pad3;           /**< Don't use this */
};



void        dbus_message_iter_init_closed        (DBusMessageIter *iter);
bool dbus_message_iter_init             (DBusMessage     *message,
                                                DBusMessageIter *iter);
bool dbus_message_iter_has_next         (DBusMessageIter *iter);
bool dbus_message_iter_next             (DBusMessageIter *iter);
char*       dbus_message_iter_get_signature    (DBusMessageIter *iter);
int         dbus_message_iter_get_arg_type     (DBusMessageIter *iter);
int         dbus_message_iter_get_element_type (DBusMessageIter *iter);
void        dbus_message_iter_recurse          (DBusMessageIter *iter,
                                                DBusMessageIter *sub);
void        dbus_message_iter_get_basic        (DBusMessageIter *iter,
                                                void            *value);
int         dbus_message_iter_get_element_count(DBusMessageIter *iter);

void        dbus_message_iter_get_fixed_array  (DBusMessageIter *iter,
                                                void            *value,
                                                int             *n_elements);


void        dbus_message_iter_init_append        (DBusMessage     *message,
                                                  DBusMessageIter *iter);
bool dbus_message_iter_append_basic       (DBusMessageIter *iter,
                                                  int              type,
                                                  const void      *value);
bool dbus_message_iter_append_fixed_array (DBusMessageIter *iter,
                                                  int              element_type,
                                                  const void      *value,
                                                  int              n_elements);
bool dbus_message_iter_open_container     (DBusMessageIter *iter,
                                                  int              type,
                                                  const char      *contained_signature,
                                                  DBusMessageIter *sub);
bool dbus_message_iter_close_container    (DBusMessageIter *iter,
                                                  DBusMessageIter *sub);
void        dbus_message_iter_abandon_container  (DBusMessageIter *iter,
                                                  DBusMessageIter *sub);

void        dbus_message_iter_abandon_container_if_open (DBusMessageIter *iter,
                                                         DBusMessageIter *sub);
const char*   dbus_message_get_path         (DBusMessage   *message);
const char*   dbus_message_get_interface    (DBusMessage   *message);
const char*   dbus_message_get_member    (DBusMessage   *message);
void  dbus_free          (void  *memory);
