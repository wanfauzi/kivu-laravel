import requests

BASE_URL = "https://fine-loops-sleep.loca.lt"


def test_landing_page_loads() -> None:
    """PRD: Landing page must load with project listings and talent section."""
    response = requests.get(f"{BASE_URL}/", timeout=30)
    assert response.status_code == 200, f"Landing page returned {response.status_code}"
    assert "KIVU" in response.text, "Landing page missing KIVU branding"
    assert "Peluang" in response.text or "peluang" in response.text, "Landing page missing Opportunities section"
    assert "Talent" in response.text or "talent" in response.text, "Landing page missing Talent section"


def test_login_page_loads() -> None:
    """PRD AUTH-02: Login page accessible."""
    response = requests.get(f"{BASE_URL}/login", timeout=30)
    assert response.status_code == 200, f"Login page returned {response.status_code}"
    assert "login" in response.text.lower() or "masuk" in response.text.lower() or "email" in response.text.lower()


def test_register_page_loads() -> None:
    """PRD AUTH-01: Register page accessible with role selection."""
    response = requests.get(f"{BASE_URL}/register", timeout=30)
    assert response.status_code == 200, f"Register page returned {response.status_code}"
    assert "register" in response.text.lower() or "daftar" in response.text.lower() or "role" in response.text.lower()


def test_student_routes_require_auth() -> None:
    """PRD AUTH-05: Role-protected routes require authentication."""
    protected_routes = [
        "/student",
        "/student/opportunities",
        "/student/wallet",
        "/student/applications",
        "/student/profile",
    ]
    for route in protected_routes:
        response = requests.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 401, 403], (
            f"Route {route} returned {response.status_code} without auth (expected redirect to login)"
        )


def test_umkm_routes_require_auth() -> None:
    """PRD AUTH-05: UMKM routes protected."""
    protected_routes = [
        "/umkm",
        "/umkm/projects",
        "/umkm/create-project",
        "/umkm/profile",
    ]
    for route in protected_routes:
        response = requests.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 401, 403], (
            f"Route {route} returned {response.status_code} without auth"
        )


def test_admin_routes_require_auth() -> None:
    """PRD AUTH-05: Admin routes protected."""
    protected_routes = [
        "/admin",
        "/admin/users",
        "/admin/projects",
        "/admin/transactions",
        "/admin/withdrawals",
        "/admin/disputes",
    ]
    for route in protected_routes:
        response = requests.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 401, 403], (
            f"Route {route} returned {response.status_code} without auth"
        )


test_landing_page_loads()
test_login_page_loads()
test_register_page_loads()
test_student_routes_require_auth()
test_umkm_routes_require_auth()
test_admin_routes_require_auth()
