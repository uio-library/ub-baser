CREATE TABLE oversatte_lover (
    lov_id          SERIAL  PRIMARY KEY,
    dato DATE       NOT NULL,
    nummer          SMALLINT,
    dok_type TEXT   check(dok_type in ('lov','forskrift')),
    sprak VARCHAR(15),
    regdato DATE,
    moddato DATE,
    tittel TEXT,
    kort TEXT,
    --url TEXT,  --kan droppes?
    note TEXT,
    UNIQUE (dato, nummer, dok_type)
);

CREATE TABLE oversettelser (
    oversettelse_id     SERIAL  PRIMARY KEY,
    lov_id  NUMBER      NOT NULL REFERENCES oversatte_lover(lov_id),
    dato DATE,
    sprak VARCHAR(15),
    regdato DATE,
    moddato DATE,
    tittel TEXT,
    kort TEXT,
    oversetter TEXT,
    bibsys TEXT,
    url TEXT,
    note TEXT,
    inote TEXT,
    utgave TEXT,
);
