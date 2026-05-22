# Deployment Guide - Cloudflare Pages

## Step 1: Create GitHub Repository

1. Go to [GitHub.com](https://github.com)
2. Click **New Repository**
3. Repository name: `moonlit-aura`
4. Description: `Moonlit Aura - Handmade Store Website`
5. Choose **Public** (for Cloudflare to access it)
6. Click **Create Repository**

## Step 2: Push Your Project to GitHub

Open PowerShell in your project folder and run:

```powershell
# Initialize git (if not already done)
git init

# Add all files
git add .

# Create first commit
git commit -m "Initial commit: Moonlit Aura static website"

# Add remote repository (replace USERNAME with your GitHub username)
git remote add origin https://github.com/USERNAME/moonlit-aura.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Deploy on Cloudflare Pages

1. Go to [Cloudflare Dashboard](https://dash.cloudflare.com)
2. Click **Pages** (left sidebar)
3. Click **Create a project** → **Connect to Git**
4. Click **GitHub** and authorize Cloudflare
5. Select your `moonlit-aura` repository
6. Click **Begin setup**

### Build Settings (Leave as default):
- **Framework preset:** None
- **Build command:** (leave empty)
- **Build output directory:** `/` (root)
- **Environment variables:** (none needed)

7. Click **Save and Deploy**

## Step 4: Your Site is Live! 🚀

After deployment completes, your site will be available at:
```
https://moonlit-aura.pages.dev
```

You can also add a custom domain through Cloudflare settings.

## Files to Push to GitHub

Make sure these files are in your repository:
```
✅ index.html
✅ handmade silver necklace.webp
✅ lavender candle.jpg
✅ crochet handbag.jpg
✅ resin art frame.webp
✅ wood box.webp
✅ handmade diary.webp
✅ clay pot.jpg
✅ wall hanging.jpeg
✅ soap.jpg
✅ customized frame.avif
✅ README.md
✅ .gitignore
✅ DEPLOYMENT.md (this file)

❌ login.php (exclude)
❌ register.php (exclude)
❌ logout.php (exclude)
❌ config.php (exclude)
❌ setup.php (exclude)
❌ save_order.php (exclude)
❌ login page.html (exclude)
```

The `.gitignore` file will automatically exclude PHP files.

## Troubleshooting

**Site shows 404:**
- Ensure `index.html` is in the root directory
- Check that all image file names match exactly (case-sensitive)

**Images not loading:**
- Verify image files are in the same folder as `index.html`
- Check image file names in `index.html` vs actual file names

**Deployment failed:**
- Check Cloudflare Pages build logs for errors
- Ensure all required files are pushed to GitHub
