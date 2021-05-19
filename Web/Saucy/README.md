# Saucy

Challenges all about analysing source code.

Summaries of challenges available below. Detailed deployment instructions available in each challenge folder.

## Saucy Part 3

A web app exploring a vulnerability allowing the viewing of the source code, and a classic OWASP vulnerability providing the solution. Requires some code analysis and knowledge of common web vulnerabilities to solve.

<details>

<summary>Spoilers</summary>

The challenge is a PHP web app with an LFI revealing the source code and a PHP deserialisation vulnerability. Players must create a 'recipe recipe' that is pulled down from the database and that reads a second recipe from the database when it is unserialised, which contains the flag.

</details>