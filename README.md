# WebAppAufgabe

Gemeinsames Github repo

## Programmieren
Wenn es geht in VS Code/VS Codium clonen und mit `git switch <Name>`
Alternativ wenn das nicht geht einen codespace Nutzen

### Git Befehle
#### Dem Eigenen Branch beitreten
```
git checkout main
git pull origin main
git switch <name>
```
#### Änderungen in den eigenen Branch schieben
```
git add .
git commit -m "Describe the changes you made"
git push origin <name>
```
#### Änderungen in dem Main Branch schieben
```
git checkout main
git pull origin main
git merge <name>
git push origin main
# Danach in den eigenen zurück
```
#### Änderungen von anderen in den Branch setzen
```
git fetch origin main
git rebase origin/main
git push origin <name> --force-with-lease
```

## Codespaces
1. Auf code dann auf codespaces. Den eigenen auswählen
2. Programmieren
3. Codespace Stoppen
