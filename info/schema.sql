
CREATE SEQUENCE fakr_inbox_id_seq;

CREATE TABLE fakr_inbox (
    id BIGINT NOT NULL PRIMARY KEY DEFAULT NEXTVAL('fakr_inbox_id_seq'::regclass),
    sender VARCHAR(255) NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    datecreated TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()
);

CREATE INDEX ON fakr_inbox(datecreated);

