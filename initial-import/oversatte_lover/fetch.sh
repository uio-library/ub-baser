wget -O oversatte_lover_lover.tsv https://app.uio.no/ub/ujur/oversatte-lover/cgi-bin/export.cgi?version=original
iconv -f ISO-8859-1 -t UTF-8 oversatte_lover_lover.tsv -o tmp.tsv
mv tmp.tsv oversatte_lover_lover.tsv

wget -O oversatte_lover_oversettelser.tsv https://app.uio.no/ub/ujur/oversatte-lover/cgi-bin/export.cgi?version=oversatt
iconv -f ISO-8859-1 -t UTF-8 oversatte_lover_oversettelser.tsv  -o tmp.tsv
mv tmp.tsv oversatte_lover_oversettelser.tsv
