#!/bin/bash

# GitHub Repository Setup Script
# Run this to prepare your Laravel Budget Manager for GitHub deployment

echo "🚀 Setting up Laravel Budget Manager for GitHub deployment..."

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📁 Initializing Git repository..."
    git init
    git branch -M main
else
    echo "✅ Git repository already initialized"
fi

# Add all files to git
echo "📦 Adding files to Git..."
git add .

# Create initial commit
echo "💾 Creating initial commit..."
git commit -m "Initial commit: Laravel Budget Manager with GitHub auto-deployment setup

Features:
- Complete budget management system
- Monthly budget templates
- Purchase tracking with smart categorization
- Investment/savings budget support
- Visual progress indicators with intelligent colors
- Responsive design with Tailwind CSS
- SQLite (dev) / MySQL (production) support
- Automated GitHub deployment configuration"

echo "✅ Repository prepared for GitHub!"
echo ""
echo "🔗 Next steps:"
echo "1. Create a new repository on GitHub.com"
echo "2. Run: git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git"
echo "3. Run: git push -u origin main"
echo "4. Follow the GITHUB_DEPLOYMENT.md guide to set up Hostinger"
echo ""
echo "📚 Available documentation:"
echo "   - README.md - Project overview and local development"
echo "   - GITHUB_DEPLOYMENT.md - Complete GitHub + Hostinger setup guide"
echo "   - DEPLOYMENT_GUIDE.md - Manual deployment instructions"
echo ""
echo "🎉 Your Laravel Budget Manager is ready for the world!"