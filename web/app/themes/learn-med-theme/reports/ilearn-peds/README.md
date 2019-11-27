# Procedure

1. Add a new block in residents.yml including the details of each of the residents of the new block on which to report (see _Adding a new block to residents.yml_ below)
2. Download a new copy of the production database to the dev environment ([see instructions](https://git.med.uottawa.ca/E-Learning/wordpress-lms/wiki/Debugging-the-prod-database))
3. Visit the report.xls.php page in the .dev environment in your browser
4. Send the generated report to the following people: "Karwowska, Anna" <Karwowska@cheo.on.ca>, CC to: "Eric Wooltorton" <ewooltorton@yahoo.com>, "Dr. Emma Stodel" <estodel@learning4excellence.com>, "Audcent, Tobey" <taudcent@cheo.on.ca>, "Kim Rozon" <krozon@bruyere.org>

# Adding a new block to residents.yml

1. Open the excel sheet for the new block from P:\Medtech\Common Files\iLearn - Peds\Residents forms
2. Arrange the excel sheet in the following columns: `LDAP - Email`, `Given Name`, `Surname`, a new column called `Level`, `User ID`, `Program`.
  * For the `Level` column, manually reproduce the PGYx level that's found in the '(PGY x xxx-xxxx Block x)' column. E.g. 'PGY1', 'PGY2', 'PGY3', 'PGY4'
3. Copy the rows with just those columns in a text file. Save it to `newblock.txt`
  * Make sure there are no double tabs in `newblock.txt` in between the columns. To do that, open `newblock.txt` in a text editor and search for double tabs and replace with a single tab character.
4. In the terminal, run the following command, which will create newblock.yml

```
echo "newblock:" > newblock.yml; cat newblock.txt | awk -F\t '{ print "  " $1 ":\n    name: " $2 " " $3 "\n    level: " $4 "\n    username: " $5 "\n    program: \"" $6 "\"" }' >> newblock.yml
```

Then, copy the contents of `newblock.yml` to the bottom of `residents.yml`, replacing the line `newblock:` with `block<number>` (e.g. block5), to follow the numbering.
