read -p "Enter your name: " name
git fetch origin main
git rebase origin/main
git push origin $name --force-with-lease