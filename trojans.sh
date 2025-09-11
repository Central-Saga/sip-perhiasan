#!/bin/bash

# ========================================
# 🦠 TROJANS.SH - SIP PERHIASAN INSTALLER
# ========================================
# ⚠️  WARNING: This script will modify your system!
# 🎯 Target: SIP Perhiasan Management System
# 🔥 Author: Central Saga Development Team
# ========================================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# ASCII Art Banner
echo -e "${RED}"
cat << "EOF"
 ╔══════════════════════════════════════════════════════════════╗
 ║                    🦠 T R O J A N S 🦠                       ║
 ╠══════════════════════════════════════════════════════════════╣
 ║  ████████╗██████╗  ██████╗      ██╗ █████╗ ███╗   ██╗███████╗║
 ║  ╚══██╔══╝██╔══██╗██╔═══██╗     ██║██╔══██╗████╗  ██║██╔════╝║
 ║     ██║   ██████╔╝██║   ██║     ██║███████║██╔██╗ ██║███████╗║
 ║     ██║   ██╔══██╗██║   ██║██   ██║██╔══██║██║╚██╗██║╚════██║║
 ║     ██║   ██║  ██║╚██████╔╝╚█████╔╝██║  ██║██║ ╚████║███████║║
 ║     ╚═╝   ╚═╝  ╚═╝ ╚═════╝  ╚════╝ ╚═╝  ╚═╝╚═╝  ╚═══╝╚══════╝║
 ╠══════════════════════════════════════════════════════════════╣
 ║        🔥 SIP PERHIASAN SYSTEM INSTALLER v1.0 🔥             ║
 ║        ⚠️  WARNING: SYSTEM MODIFICATION IN PROGRESS  ⚠️      ║
 ╚══════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

echo -e "${PURPLE}┌─────────────────────────────────────────────────────────────┐${NC}"
echo -e "${PURPLE}│  🦠 TROJANS.SH - SIP Perhiasan System Installer v1.0 🦠     │${NC}"
echo -e "${PURPLE}├─────────────────────────────────────────────────────────────┤${NC}"
echo -e "${YELLOW}│  ⚠️  WARNING: This script will modify your system!         │${NC}"
echo -e "${CYAN}│  🎯 Target: SIP Perhiasan Management System       │${NC}"
echo -e "${WHITE}│  🔥 Author: Central Saga Development Team                  │${NC}"
echo -e "${GREEN}│  📅 Build: $(date '+%Y-%m-%d %H:%M:%S')                           │${NC}"
echo -e "${PURPLE}└─────────────────────────────────────────────────────────────┘${NC}"
echo ""
echo -e "${RED}🚨 SYSTEM INFILTRATION INITIATED... 🚨${NC}"
echo -e "${YELLOW}💀 Preparing to inject malicious... I mean helpful code! 💀${NC}"
echo ""

# Function to print step
print_step() {
    echo -e "${BLUE}[STEP $1]${NC} ${GREEN}$2${NC}"
}

# Function to print success
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Function to print error
print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Function to print warning
print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found! Please run this script from the project root directory."
    exit 1
fi

print_step "1" "Initializing Trojan Installation..."
sleep 2

# Step 1: Composer Update
print_step "2" "Updating Composer Dependencies..."
echo -e "${CYAN}🔄 Running: composer update${NC}"
if composer update --no-interaction; then
    print_success "Composer dependencies updated successfully"
else
    print_error "Failed to update composer dependencies"
    exit 1
fi
echo ""

# Step 2: NPM Install and Build
print_step "3" "Installing and Building NPM Packages..."
echo -e "${CYAN}🔄 Running: npm install${NC}"
if npm install; then
    print_success "NPM packages installed successfully"
else
    print_error "Failed to install NPM packages"
    exit 1
fi

echo -e "${CYAN}🔄 Running: npm run build${NC}"
if npm run build; then
    print_success "NPM build completed successfully"
else
    print_error "Failed to build NPM packages"
    exit 1
fi
echo ""

# Step 3: Environment Setup
print_step "4" "Setting up Environment Configuration..."
if [ ! -f ".env" ]; then
    echo -e "${CYAN}🔄 Copying .env.example to .env${NC}"
    cp .env.example .env
    print_success "Environment file created"
else
    print_warning ".env file already exists, skipping copy"
fi

# Update .env for MySQL
echo -e "${CYAN}🔄 Configuring MySQL database settings${NC}"
sed -i.bak 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i.bak 's/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
sed -i.bak 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i.bak 's/# DB_DATABASE=laravel/DB_DATABASE=sip_perhiasan/' .env
sed -i.bak 's/# DB_USERNAME=root/DB_USERNAME=root/' .env
sed -i.bak 's/# DB_PASSWORD=/DB_PASSWORD=/' .env
print_success "MySQL configuration updated"
echo ""

# Step 4: Generate Application Key
print_step "5" "Generating Application Key..."
echo -e "${CYAN}🔄 Running: php artisan key:generate${NC}"
if php artisan key:generate --force; then
    print_success "Application key generated successfully"
else
    print_error "Failed to generate application key"
    exit 1
fi
echo ""

# Step 5: Database Setup
print_step "6" "Setting up Database..."
echo -e "${CYAN}🔄 Running: php artisan migrate${NC}"
if php artisan migrate --force; then
    print_success "Database migrations completed successfully"
else
    print_error "Failed to run database migrations"
    exit 1
fi

echo -e "${CYAN}🔄 Running: php artisan db:seed${NC}"
if php artisan db:seed --force; then
    print_success "Database seeding completed successfully"
else
    print_error "Failed to seed database"
    exit 1
fi
echo ""

# Step 6: Git Operations
print_step "7" "Git Operations - Creating New Branch and Committing..."
BRANCH_NAME="trojan-install-$(date +%Y%m%d-%H%M%S)"
echo -e "${CYAN}🔄 Creating new branch: $BRANCH_NAME${NC}"

# Check if there are changes to commit
if git diff --quiet && git diff --staged --quiet; then
    print_warning "No changes to commit"
else
    # Create new branch
    git checkout -b "$BRANCH_NAME"

    # Add all changes
    git add .

    # Commit changes
    git commit -m "🦠 Trojan installation: Update dependencies and configuration

    - Updated composer dependencies
    - Built NPM packages
    - Configured MySQL database
    - Generated application key
    - Ran migrations and seeders

    Installed by trojans.sh script"

    print_success "Changes committed to branch: $BRANCH_NAME"

    # Merge to main
    echo -e "${CYAN}🔄 Merging to main branch${NC}"
    git checkout main
    git merge "$BRANCH_NAME" --no-ff -m "🦠 Merge trojan installation: $BRANCH_NAME"

    print_success "Successfully merged to main branch"
fi
echo ""

# Step 7: Final Setup
print_step "8" "Final System Configuration..."
echo -e "${CYAN}🔄 Clearing caches${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared successfully"
echo ""

# Step 7.1: Storage Link
print_step "8.1" "Linking Storage Directory..."
echo -e "${CYAN}🔄 Running: php artisan storage:link${NC}"
if php artisan storage:link; then
    print_success "Storage linked successfully"
else
    print_error "Failed to link storage directory"
    exit 1
fi
echo ""

# Step 8: Start Development Server
# Step 8: Start Development Server
print_step "9" "Starting Development Environment..."
echo -e "${GREEN}🚀 System Installation Complete!${NC}"
echo ""
echo -e "${WHITE}📋 Installation Summary:${NC}"
echo -e "${CYAN}   ✅ Composer dependencies updated${NC}"
echo -e "${CYAN}   ✅ NPM packages installed and built${NC}"
echo -e "${CYAN}   ✅ Environment configured for MySQL${NC}"
echo -e "${CYAN}   ✅ Application key generated${NC}"
echo -e "${CYAN}   ✅ Database migrated and seeded${NC}"
echo -e "${CYAN}   ✅ Git operations completed${NC}"
echo -e "${CYAN}   ✅ Caches cleared${NC}"
echo -e "${CYAN}   ✅ Storage linked${NC}"   # 👈 tambahan summary disini
echo ""
echo -e "${YELLOW}🔐 Default Login Credentials:${NC}"
echo -e "${WHITE}   Admin: admin@example.com / password${NC}"
echo -e "${WHITE}   User:  pengunjung@example.com / password${NC}"
echo ""
echo -e "${GREEN}🎯 Starting development server...${NC}"
echo -e "${CYAN}   Server will be available at: http://localhost:8000${NC}"
echo -e "${CYAN}   Press Ctrl+C to stop the server${NC}"
echo ""

# Start the development server
echo -e "${RED}┌─────────────────────────────────────────────────────────────┐${NC}"
echo -e "${RED}│                🦠 TROJAN ACTIVATED! 🦠                      │${NC}"
echo -e "${RED}├─────────────────────────────────────────────────────────────┤${NC}"
echo -e "${GREEN}│  ✅ System infiltration complete!                          │${NC}"
echo -e "${CYAN}│  🎯 Pondok Putri system is now ONLINE!                     │${NC}"
echo -e "${YELLOW}│  🚀 Development server launching...                        │${NC}"
echo -e "${WHITE}│  📍 Server: http://localhost:8000                          │${NC}"
echo -e "${PURPLE}│  💀 All your base are belong to us! 💀                    │${NC}"
echo -e "${RED}└─────────────────────────────────────────────────────────────┘${NC}"
echo ""
echo -e "${GREEN}🎉 Trojans have successfully infiltrated your system! 🎉${NC}"
echo -e "${CYAN}🔥 Launching SIP Perhiasan System... 🔥${NC}"
echo ""

# Countdown untuk dramatic effect
for i in {3..1}; do
    echo -e "${YELLOW}🚀 Starting in $i...${NC}"
    sleep 1
done

echo -e "${GREEN}🎯 SERVER ONLINE! 🎯${NC}"
echo ""
php artisan serve --host=0.0.0.0 --port=8000
