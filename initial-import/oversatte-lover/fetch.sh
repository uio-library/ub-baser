wget -O oversatte_lover.tsv https://app.uio.no/ub/ujur/oversatte-lover/cgi-bin/export.cgi?version=original
iconv -f ISO-8859-1 -t UTF-8 oversatte_lover.tsv -o tmp.tsv
mv tmp.tsv oversatte_lover.tsv

wget -O oversettelser.tsv https://app.uio.no/ub/ujur/oversatte-lover/cgi-bin/export.cgi?version=oversatt
iconv -f ISO-8859-1 -t UTF-8 oversettelser.tsv  -o tmp.tsv
mv tmp.tsv oversettelser.tsv