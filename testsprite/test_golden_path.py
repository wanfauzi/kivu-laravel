import requests
import re
import uuid

BASE_URL = "https://fine-loops-sleep.loca.lt"


def _get_csrf_token(session: requests.Session) -> str:
    resp = session.get(f"{BASE_URL}/login", timeout=30)
    match = re.search(r'name="_token"\s+value="([^"]+)"', resp.text)
    if match:
        return match.group(1)
    match = re.search(r'content="([^"]+)"\s+name="csrf-token"', resp.text)
    if match:
        return match.group(1)
    return ""


def _login(session: requests.Session, email: str, password: str) -> requests.Response:
    token = _get_csrf_token(session)
    return session.post(
        f"{BASE_URL}/login",
        data={"email": email, "password": password, "_token": token},
        timeout=30,
        allow_redirects=True,
    )


def _livewire_update(session: requests.Session, component: str, data: dict, snapshot: str = "") -> dict:
    """Send a Livewire update request."""
    token = _get_csrf_token(session)
    payload = {
        "_token": token,
        "components": [
            {
                "snapshot": snapshot,
                "updates": data,
                "calls": [],
            }
        ],
    }
    resp = session.post(
        f"{BASE_URL}/livewire-3b4ecae4/update",
        json=payload,
        timeout=30,
    )
    if resp.status_code == 200:
        try:
            return resp.json()
        except Exception:
            return {}
    return {"error": resp.status_code}


def test_student_can_see_opportunities() -> None:
    """PRD STU-01: Student sees OPEN projects in Opportunities page."""
    session = requests.Session()
    _login(session, "talent@kivu.id", "password")

    response = session.get(f"{BASE_URL}/student/opportunities", timeout=30)
    assert response.status_code == 200, f"Opportunities page returned {response.status_code}"
    assert "peluang" in response.text.lower() or "opportunities" in response.text.lower() or "project" in response.text.lower(), (
        "Opportunities page missing expected content"
    )


def test_student_wallet_page_loads() -> None:
    """PRD STU-06: Student wallet shows balance and transactions."""
    session = requests.Session()
    _login(session, "talent@kivu.id", "password")

    response = session.get(f"{BASE_URL}/student/wallet", timeout=30)
    assert response.status_code == 200, f"Wallet page returned {response.status_code}"
    assert "wallet" in response.text.lower() or "dompet" in response.text.lower() or "saldo" in response.text.lower() or "balance" in response.text.lower(), (
        "Wallet page missing expected content"
    )


def test_student_applications_page_loads() -> None:
    """PRD STU-04: Student can view their applications and statuses."""
    session = requests.Session()
    _login(session, "talent@kivu.id", "password")

    response = session.get(f"{BASE_URL}/student/applications", timeout=30)
    assert response.status_code == 200, f"Applications page returned {response.status_code}"


def test_umkm_can_see_projects() -> None:
    """PRD BUS-02: UMKM sees their projects page."""
    session = requests.Session()
    _login(session, "umkm@kivu.id", "password")

    response = session.get(f"{BASE_URL}/umkm/projects", timeout=30)
    assert response.status_code == 200, f"My Projects page returned {response.status_code}"
    assert (
        "proyek" in response.text.lower()
        or "project" in response.text.lower()
        or "folder-kanban" in response.text.lower()
    ), "My Projects page missing project content"


def test_umkm_create_project_page_loads() -> None:
    """PRD BUS-01: UMKM can access create project page."""
    session = requests.Session()
    _login(session, "umkm@kivu.id", "password")

    response = session.get(f"{BASE_URL}/umkm/create-project", timeout=30)
    assert response.status_code == 200, f"Create Project page returned {response.status_code}"


def test_admin_dashboard_loads() -> None:
    """PRD ADM-01: Admin dashboard shows metrics."""
    session = requests.Session()
    _login(session, "admin@kivu.id", "password")

    response = session.get(f"{BASE_URL}/admin", timeout=30)
    assert response.status_code == 200, f"Admin dashboard returned {response.status_code}"
    assert "admin" in response.text.lower(), "Admin dashboard missing admin content"


def test_admin_users_page_loads() -> None:
    """PRD ADM-02: Admin can manage users."""
    session = requests.Session()
    _login(session, "admin@kivu.id", "password")

    response = session.get(f"{BASE_URL}/admin/users", timeout=30)
    assert response.status_code == 200, f"Admin users page returned {response.status_code}"


def test_admin_transactions_page_loads() -> None:
    """PRD ADM-01: Admin can view transactions."""
    session = requests.Session()
    _login(session, "admin@kivu.id", "password")

    response = session.get(f"{BASE_URL}/admin/transactions", timeout=30)
    assert response.status_code == 200, f"Admin transactions page returned {response.status_code}"


def test_student_cannot_access_umkm_routes() -> None:
    """PRD AUTH-05 + use case rules: Student cannot access UMKM routes."""
    session = requests.Session()
    _login(session, "talent@kivu.id", "password")

    protected_routes = ["/umkm", "/umkm/projects", "/umkm/create-project"]
    for route in protected_routes:
        response = session.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 403], (
            f"Student accessed {route} (got {response.status_code}, expected 302/403)"
        )


def test_student_cannot_access_admin_routes() -> None:
    """PRD AUTH-05: Student cannot access admin routes."""
    session = requests.Session()
    _login(session, "talent@kivu.id", "password")

    protected_routes = ["/admin", "/admin/users", "/admin/projects"]
    for route in protected_routes:
        response = session.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 403], (
            f"Student accessed {route} (got {response.status_code}, expected 302/403)"
        )


def test_umkm_cannot_access_student_routes() -> None:
    """PRD AUTH-05: UMKM cannot access student routes."""
    session = requests.Session()
    _login(session, "umkm@kivu.id", "password")

    protected_routes = ["/student", "/student/opportunities", "/student/wallet"]
    for route in protected_routes:
        response = session.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 403], (
            f"UMKM accessed {route} (got {response.status_code}, expected 302/403)"
        )


def test_umkm_cannot_access_admin_routes() -> None:
    """PRD AUTH-05: UMKM cannot access admin routes."""
    session = requests.Session()
    _login(session, "umkm@kivu.id", "password")

    protected_routes = ["/admin", "/admin/users", "/admin/transactions"]
    for route in protected_routes:
        response = session.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 403], (
            f"UMKM accessed {route} (got {response.status_code}, expected 302/403)"
        )


def test_admin_cannot_access_student_routes() -> None:
    """PRD AUTH-05: Admin cannot access student routes."""
    session = requests.Session()
    _login(session, "admin@kivu.id", "password")

    protected_routes = ["/student", "/student/opportunities", "/student/wallet"]
    for route in protected_routes:
        response = session.get(f"{BASE_URL}{route}", timeout=30, allow_redirects=False)
        assert response.status_code in [302, 403], (
            f"Admin accessed {route} (got {response.status_code}, expected 302/403)"
        )


def test_umkm_cannot_access_student_profile() -> None:
    """PRD AUTH-05 + use case rules: UMKM cannot access student-specific pages."""
    session = requests.Session()
    _login(session, "umkm@kivu.id", "password")

    response = session.get(f"{BASE_URL}/student/profile", timeout=30, allow_redirects=False)
    assert response.status_code in [302, 403], (
        f"UMKM accessed /student/profile (got {response.status_code})"
    )


test_student_can_see_opportunities()
test_student_wallet_page_loads()
test_student_applications_page_loads()
test_umkm_can_see_projects()
test_umkm_create_project_page_loads()
test_admin_dashboard_loads()
test_admin_users_page_loads()
test_admin_transactions_page_loads()
test_student_cannot_access_umkm_routes()
test_student_cannot_access_admin_routes()
test_umkm_cannot_access_student_routes()
test_umkm_cannot_access_admin_routes()
test_admin_cannot_access_student_routes()
test_umkm_cannot_access_student_profile()
