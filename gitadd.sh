while read line; do
    git add  -u $line --verbose 
       done
       #for i in `ls` ; do echo add $i --verbose --dry-run;   done
       git status --untracked-files=no
