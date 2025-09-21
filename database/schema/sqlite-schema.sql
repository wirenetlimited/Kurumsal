CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "theme" varchar not null default 'light',
  "theme_color" varchar not null default 'blue',
  "dashboard_title" varchar not null default 'Takip Sistemi Dashboard',
  "is_admin" tinyint(1) not null default '0'
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "customers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "tax_number" varchar,
  "email" varchar,
  "phone" varchar,
  "address" varchar,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "customer_type" varchar not null default 'individual',
  "surname" varchar,
  "phone_mobile" varchar,
  "website" varchar,
  "city" varchar,
  "district" varchar,
  "zip" varchar,
  "country" varchar,
  "invoice_address" text,
  "invoice_city" varchar,
  "invoice_district" varchar,
  "invoice_zip" varchar,
  "invoice_country" varchar,
  "notes" text
);
CREATE TABLE IF NOT EXISTS "providers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar not null,
  "contact_info" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "services"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "provider_id" integer,
  "status" varchar check("status" in('active', 'suspended', 'cancelled', 'expired')) not null default 'active',
  "start_date" date,
  "end_date" date,
  "cycle" varchar check("cycle" in('monthly', 'quarterly', 'semiannually', 'yearly', 'biennially', 'triennially')) not null default 'yearly',
  "cost_price" numeric not null default '0',
  "sell_price" numeric not null default '0',
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  "payment_type" varchar check("payment_type" in('upfront', 'installment')) not null default 'upfront',
  "service_type" varchar,
  foreign key("customer_id") references "customers"("id") on delete cascade,
  foreign key("provider_id") references "providers"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "domains"(
  "id" integer primary key autoincrement not null,
  "service_id" integer not null,
  "domain_name" varchar not null,
  "registrar_ref" varchar,
  "auth_code" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("service_id") references "services"("id") on delete cascade
);
CREATE INDEX "domains_domain_name_index" on "domains"("domain_name");
CREATE TABLE IF NOT EXISTS "hostings"(
  "id" integer primary key autoincrement not null,
  "service_id" integer not null,
  "plan_name" varchar,
  "server_name" varchar,
  "ip_address" varchar,
  "cpanel_username" varchar,
  "panel_ref" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("service_id") references "services"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "invoices"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "issue_date" date not null,
  "due_date" date,
  "status" varchar check("status" in('draft', 'sent', 'paid', 'overdue', 'cancelled')) not null default 'draft',
  "currency" varchar not null default 'TRY',
  "subtotal" numeric not null default '0',
  "tax_total" numeric not null default '0',
  "total" numeric not null default '0',
  "withholding" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "invoice_number" varchar,
  foreign key("customer_id") references "customers"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "invoice_items"(
  "id" integer primary key autoincrement not null,
  "invoice_id" integer not null,
  "service_id" integer,
  "description" varchar not null,
  "qty" numeric not null default '1',
  "unit_price" numeric not null,
  "tax_rate" numeric not null default '0',
  "line_total" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("invoice_id") references "invoices"("id") on delete cascade,
  foreign key("service_id") references "services"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "ledger_entries"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "related_type" varchar not null,
  "related_id" integer not null,
  "entry_date" date not null,
  "debit" numeric not null default '0',
  "credit" numeric not null default '0',
  "balance_after" numeric not null default '0',
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("customer_id") references "customers"("id") on delete cascade
);
CREATE INDEX "ledger_entries_related_type_related_id_index" on "ledger_entries"(
  "related_type",
  "related_id"
);
CREATE TABLE IF NOT EXISTS "payments"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "invoice_id" integer,
  "method" varchar,
  "amount" numeric not null,
  "paid_at" datetime,
  "reference" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "notes" text,
  foreign key("customer_id") references "customers"("id") on delete cascade,
  foreign key("invoice_id") references "invoices"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "reminders"(
  "id" integer primary key autoincrement not null,
  "remindable_type" varchar,
  "remindable_id" integer,
  "reminder_type" varchar not null,
  "sent_at" datetime,
  "channel" varchar not null default 'mail',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "reminders_remindable_type_remindable_id_index" on "reminders"(
  "remindable_type",
  "remindable_id"
);
CREATE TABLE IF NOT EXISTS "quote_items"(
  "id" integer primary key autoincrement not null,
  "quote_id" integer not null,
  "description" varchar not null,
  "qty" numeric not null default '1',
  "unit_price" numeric not null default '0',
  "line_total" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("quote_id") references "quotes"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "quotes"(
  "id" integer primary key autoincrement not null,
  "number" varchar not null,
  "customer_id" integer,
  "customer_name" varchar,
  "customer_email" varchar,
  "customer_phone" varchar,
  "title" varchar,
  "status" varchar check("status" in('draft', 'sent', 'accepted', 'rejected', 'expired')) not null default 'draft',
  "quote_date" date,
  "valid_until" date,
  "discount_amount" numeric not null default '0',
  "tax_rate" numeric not null default '0',
  "subtotal" numeric not null default '0',
  "tax_total" numeric not null default '0',
  "total" numeric not null default '0',
  "notes" text,
  "terms" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("customer_id") references "customers"("id") on delete set null
);
CREATE UNIQUE INDEX "quotes_number_unique" on "quotes"("number");
CREATE TABLE IF NOT EXISTS "settings"(
  "id" integer primary key autoincrement not null,
  "group" varchar not null,
  "key" varchar not null,
  "value" text,
  "type" varchar not null default 'text',
  "label" varchar not null,
  "description" text,
  "options" text,
  "is_public" tinyint(1) not null default '0',
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "settings_group_index" on "settings"("group");
CREATE UNIQUE INDEX "settings_key_unique" on "settings"("key");
CREATE INDEX "services_payment_type_cycle_index" on "services"(
  "payment_type",
  "cycle"
);
CREATE INDEX "invoices_invoice_number_index" on "invoices"("invoice_number");
CREATE UNIQUE INDEX "invoices_invoice_number_unique" on "invoices"(
  "invoice_number"
);
CREATE INDEX "ledger_entries_customer_id_index" on "ledger_entries"(
  "customer_id"
);
CREATE INDEX "ledger_entries_customer_id_entry_date_index" on "ledger_entries"(
  "customer_id",
  "entry_date"
);
CREATE INDEX "ledger_entries_customer_id_debit_index" on "ledger_entries"(
  "customer_id",
  "debit"
);
CREATE INDEX "ledger_entries_customer_id_credit_index" on "ledger_entries"(
  "customer_id",
  "credit"
);
CREATE INDEX "services_customer_id_index" on "services"("customer_id");
CREATE INDEX "services_status_index" on "services"("status");
CREATE INDEX "services_customer_id_status_index" on "services"(
  "customer_id",
  "status"
);
CREATE INDEX "services_payment_type_index" on "services"("payment_type");
CREATE INDEX "services_start_date_index" on "services"("start_date");
CREATE INDEX "idx_invoices_status_customer" on "invoices"(
  "status",
  "customer_id"
);
CREATE INDEX "idx_invoices_status_total" on "invoices"("status", "total");
CREATE INDEX "idx_invoices_date" on "invoices"("invoice_date");
CREATE INDEX "idx_ledger_customer_type" on "ledger_entries"(
  "customer_id",
  "entry_type"
);
CREATE INDEX "idx_ledger_invoice_type" on "ledger_entries"(
  "invoice_id",
  "entry_type"
);
CREATE INDEX "idx_ledger_date" on "ledger_entries"("entry_date");
CREATE INDEX "idx_ledger_amounts" on "ledger_entries"("debit", "credit");
CREATE INDEX "idx_customers_active" on "customers"("is_active");
CREATE TABLE IF NOT EXISTS "accounting_ledger_entries"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "invoice_id" integer,
  "payment_id" integer,
  "type" varchar check("type" in('debit', 'credit', 'reverse')) not null,
  "amount" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("customer_id") references "customers"("id") on delete cascade,
  foreign key("invoice_id") references "invoices"("id") on delete cascade,
  foreign key("payment_id") references "payments"("id") on delete cascade
);
CREATE UNIQUE INDEX "unique_debit_per_invoice" on "accounting_ledger_entries"(
  "invoice_id",
  "type"
);
CREATE UNIQUE INDEX "unique_credit_per_payment" on "accounting_ledger_entries"(
  "payment_id",
  "type"
);
CREATE INDEX "accounting_ledger_entries_customer_id_type_index" on "accounting_ledger_entries"(
  "customer_id",
  "type"
);
CREATE INDEX "accounting_ledger_entries_created_at_index" on "accounting_ledger_entries"(
  "created_at"
);
CREATE INDEX "invoices_customer_id_status_index" on "invoices"(
  "customer_id",
  "status"
);
CREATE INDEX "payments_customer_id_index" on "payments"("customer_id");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_08_12_212329_create_core_tables',1);
INSERT INTO migrations VALUES(5,'2025_08_12_222501_add_theme_to_users_table',1);
INSERT INTO migrations VALUES(6,'2025_08_12_222937_add_theme_color_to_users_table',1);
INSERT INTO migrations VALUES(7,'2025_08_12_230600_create_quote_items_table',1);
INSERT INTO migrations VALUES(8,'2025_08_12_230600_create_quotes_table',1);
INSERT INTO migrations VALUES(9,'2025_08_13_173955_add_dashboard_title_to_users_table',1);
INSERT INTO migrations VALUES(10,'2025_08_13_195722_add_customer_type_to_customers_table',2);
INSERT INTO migrations VALUES(11,'2025_08_13_231800_add_extended_fields_to_customers_table',3);
INSERT INTO migrations VALUES(12,'2025_08_14_201025_create_settings_table',4);
INSERT INTO migrations VALUES(13,'2025_08_15_005800_add_payment_type_to_services_table',5);
INSERT INTO migrations VALUES(15,'2025_08_15_012552_add_invoice_number_to_invoices_table',6);
INSERT INTO migrations VALUES(16,'2025_08_15_012820_add_unique_constraint_to_invoice_number',6);
INSERT INTO migrations VALUES(17,'2025_08_15_021930_add_is_admin_to_users_table',7);
INSERT INTO migrations VALUES(18,'2025_08_15_023628_alter_services_service_type_to_string',8);
INSERT INTO migrations VALUES(19,'2025_08_15_210634_add_indexes_to_ledger_entries_table',9);
INSERT INTO migrations VALUES(20,'2025_08_15_210650_add_indexes_to_services_table',9);
INSERT INTO migrations VALUES(21,'2025_08_15_212752_encrypt_existing_sensitive_settings',10);
INSERT INTO migrations VALUES(22,'2025_08_16_130842_update_theme_colors_to_valid_values',11);
INSERT INTO migrations VALUES(23,'2025_08_17_020000_add_reconciliation_indexes',12);
INSERT INTO migrations VALUES(24,'2025_08_17_005502_update_payments_table_structure',13);
INSERT INTO migrations VALUES(25,'2025_08_17_015208_create_accounting_ledger_entries_table',14);
INSERT INTO migrations VALUES(26,'2025_08_17_023335_add_customer_balance_indexes',15);
