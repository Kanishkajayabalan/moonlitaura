# Deployment Guide - Cloudflare Pages

## ⚠️ CRITICAL - Build Settings MUST Be Empty!

For Cloudflare Pages to build without errors, **the Build command MUST be EMPTY**:

1. Go to [https://dash.cloudflare.com](https://dash.cloudflare.com)
2. Click **Pages** → **moonlitaura**
3. Click **Settings** → **Builds & Deployments**
4. Find the **"Build command"** field
5. **DELETE everything in it** - leave it COMPLETELY EMPTY
6. Ensure **"Build output directory"** is set to: `.`
7. Click **Save**
8. Go to **Deployments** tab
9. Find your failed deployment and click **Retry**
10. Wait 30-60 seconds for the build to complete ✅

**Why?** This is a static HTML website - it doesn't need a build process. Cloudflare Pages will serve the files directly.

---

## Step 1: Create GitHub Repository

1. Go to [GitHub.com](https://github.com)
2. Click **New Repository**
3. Repository name: `moonlit-aura` (or your preferred name)
4. Description: `Moonlit Aura - Handmade Store Website`
5. Choose **Public** (for Cloudflare to access it)
6. Click **Create Repository**

---

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

---

## Step 3: Deploy on Cloudflare Pages

1. Go to [Cloudflare Dashboard](https://dash.cloudflare.com)
2. Click **Pages** (left sidebar)
3. Click **Create a project** → **Connect to Git**
4. Click **GitHub** and authorize Cloudflare
5. Select your `moonlit-aura` repository
6. Click **Begin setup**

### Build Settings (Most Important!):
- **Framework preset:** None
- **Build command:** ⚠️ **LEAVE EMPTY** (don't type anything)
- **Build output directory:** `.` (just a single period)
- **Environment variables:** None needed

7. Click **Save and Deploy**

---

## Step 4: Your Site is Live! 🚀

After deployment completes, your site will be available at:
```
https://moonlitaura.pages.dev
```

---

## Pushing Updates to GitHub

To push new changes:

```powershell
cd "c:\Users\kanis\OneDrive\Desktop\website"
git add .
git commit -m "Your update message"
git push origin main
```

Cloudflare will automatically rebuild and deploy when you push to the main branch.

---

## Troubleshooting

**Q: Still seeing "Executing user deploy command" error?**
A: The Build command field is not empty. Go to Cloudflare Settings → Builds & Deployments and completely clear the "Build command" field.

**Q: Images not loading?**
A: Check that all image files are in your project folder and committed to Git.

**Q: Site shows old content?**
A: Clear your browser cache (Ctrl+Shift+Delete) and reload the page.

---

**Last Updated**: May 28, 2026  
**Repository**: https://github.com/Kanishkajayabalan/moonlitaura


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
