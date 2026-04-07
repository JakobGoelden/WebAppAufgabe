read -p "Enter your name: " name
git checkout main
git pull origin main
git merge origin/$name
git push origin main
git checkout $name