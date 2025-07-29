# How to Delete a Commit in Git

## 🚨 **Before You Start**
⚠️ **WARNING**: These commands can permanently delete commits. Make sure you have a backup or the commit is pushed to a remote repository.

## 📋 **Different Scenarios**

### **1. Delete the Last Commit (Not Pushed)**
```bash
# Soft delete - keeps changes in working directory
git reset --soft HEAD~1

# Hard delete - completely removes the commit and changes
git reset --hard HEAD~1

# Mixed delete - keeps changes but unstages them
git reset HEAD~1
```

### **2. Delete Multiple Recent Commits**
```bash
# Delete last 3 commits (replace 3 with number of commits)
git reset --hard HEAD~3
```

### **3. Delete a Specific Commit (Interactive Rebase)**
```bash
# Start interactive rebase
git rebase -i HEAD~5  # Shows last 5 commits

# In the editor, change 'pick' to 'drop' for commits you want to delete
# Save and exit
```

### **4. Delete Commits That Are Already Pushed**
```bash
# First, delete locally
git reset --hard HEAD~1

# Force push to remote (DANGEROUS!)
git push --force-with-lease origin main
```

### **5. Delete a Commit from Middle of History**
```bash
# Interactive rebase to the commit before the one you want to delete
git rebase -i <commit-hash-before-the-one-to-delete>

# In editor, change 'pick' to 'drop' for the commit to delete
# Save and exit
```

## 🔧 **Step-by-Step Examples**

### **Example 1: Delete Last Commit (Safe)**
```bash
# Check current status
git log --oneline -5

# Delete last commit but keep changes
git reset --soft HEAD~1

# Check status
git status
```

### **Example 2: Delete Last Commit (Complete)**
```bash
# Delete last commit and all changes
git reset --hard HEAD~1

# Verify
git log --oneline -5
```

### **Example 3: Interactive Rebase**
```bash
# Start interactive rebase
git rebase -i HEAD~3

# Editor opens with something like:
# pick abc1234 First commit
# pick def5678 Second commit  
# pick ghi9012 Third commit

# Change 'pick' to 'drop' for commits you want to delete:
# pick abc1234 First commit
# drop def5678 Second commit  # This commit will be deleted
# pick ghi9012 Third commit

# Save and exit (Ctrl+X, Y, Enter in nano)
```

## 🛡️ **Safe Practices**

### **Before Deleting:**
```bash
# Create a backup branch
git branch backup-before-delete

# Check what you're about to delete
git log --oneline -10
```

### **If You Make a Mistake:**
```bash
# Recover from backup branch
git checkout backup-before-delete

# Or use reflog to find the deleted commit
git reflog
git reset --hard <commit-hash-from-reflog>
```

## ⚠️ **Important Warnings**

### **Never Force Push to Shared Branches**
- `git push --force` can destroy other people's work
- Use `git push --force-with-lease` instead
- Only force push to your own feature branches

### **Team Communication**
- Tell your team before force pushing
- Coordinate with others working on the same branch
- Consider using `git revert` instead of deleting

## 🔄 **Alternative: Revert Instead of Delete**
```bash
# Create a new commit that undoes the changes
git revert <commit-hash>

# This is safer for shared repositories
```

## 📝 **Quick Reference**

| Action | Command | Safe for Shared Repos? |
|--------|---------|------------------------|
| Delete last commit (keep changes) | `git reset --soft HEAD~1` | ✅ |
| Delete last commit (remove changes) | `git reset --hard HEAD~1` | ✅ |
| Delete specific commit | `git rebase -i HEAD~n` | ⚠️ |
| Force push | `git push --force-with-lease` | ❌ |
| Revert commit | `git revert <hash>` | ✅ |

## 🆘 **Need Help?**

If you're unsure:
1. **What type of commit do you want to delete?** (last, specific, multiple)
2. **Is it already pushed to remote?**
3. **Are you working alone or with a team?**

Let me know and I'll give you the exact commands! 