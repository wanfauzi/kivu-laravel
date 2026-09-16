import requests
from urllib.parse import urljoin

BASE_URL = "https://fine-loops-sleep.loca.lt"


def _get_csrf_token(session: requests.Session) -> str:
    """Extract CSRF token from session cookie/page."""
    resp = session.get(f"{BASE_URL}/login", timeout=30)
    # Look for _token in the HTML
    import re
    match = re.search(r'name="_token"\s+value="([^"]+)"', resp.text)
    if match:
        return match.group(1)
    # Fallback: check meta tag
    match = re.search(r'content="([^"]+)"\s+name="csrf-token"', resp.text)
    if match:
        return match.group(1)
    return ""


def test_login_with_demo_student() -> None:
    """PRD AUTH-02 + section 17: talent@kivu.id / password logs in as student."""
    session = requests.Session()
    token = _get_csrf_token(session)

    response = session.post(
        f"{BASE_URL}/login",
        data={
            "email": "talent@kivu.id",
            "password": "password",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Login POST returned {response.status_code}"
    # After login, should redirect to /student (student dashboard)
    assert "/student" in response.url or "student" in response.text.lower(), (
        f"Student login did not redirect to dashboard. Final URL: {response.url}"
    )


def test_login_with_demo_umkm() -> None:
    """PRD AUTH-02 + section 17: umkm@kivu.id / password logs in as UMKM."""
    session = requests.Session()
    token = _get_csrf_token(session)

    response = session.post(
        f"{BASE_URL}/login",
        data={
            "email": "umkm@kivu.id",
            "password": "password",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Login POST returned {response.status_code}"
    assert "/umkm" in response.url or "umkm" in response.text.lower(), (
        f"UMKM login did not redirect to dashboard. Final URL: {response.url}"
    )


def test_login_with_demo_admin() -> None:
    """PRD AUTH-03 + section 17: admin@kivu.id / password logs in as admin."""
    session = requests.Session()
    token = _get_csrf_token(session)

    response = session.post(
        f"{BASE_URL}/login",
        data={
            "email": "admin@kivu.id",
            "password": "password",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Login POST returned {response.status_code}"
    assert "/admin" in response.url or "admin" in response.text.lower(), (
        f"Admin login did not redirect to dashboard. Final URL: {response.url}"
    )


def test_login_wrong_password_rejected() -> None:
    """PRD AUTH-02: Invalid credentials rejected with error."""
    session = requests.Session()
    token = _get_csrf_token(session)

    response = session.post(
        f"{BASE_URL}/login",
        data={
            "email": "talent@kivu.id",
            "password": "wrongpassword",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200
    assert "salah" in response.text.lower() or "wrong" in response.text.lower() or "error" in response.text.lower(), (
        "Wrong password should show error message"
    )


def test_login_nonexistent_user_rejected() -> None:
    """PRD AUTH-02: Non-existent user rejected."""
    session = requests.Session()
    token = _get_csrf_token(session)

    response = session.post(
        f"{BASE_URL}/login",
        data={
            "email": "nonexistent@test.com",
            "password": "password",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200
    assert "salah" in response.text.lower() or "wrong" in response.text.lower() or "error" in response.text.lower(), (
        "Non-existent user should show error"
    )


def test_register_student() -> None:
    """PRD AUTH-01: Student registration creates account."""
    import uuid
    session = requests.Session()
    token = _get_csrf_token(session)

    unique_email = f"teststudent_{uuid.uuid4().hex[:8]}@gmail.com"
    response = session.post(
        f"{BASE_URL}/register",
        data={
            "name": "Test Student",
            "email": unique_email,
            "password": "password123",
            "password_confirmation": "password123",
            "role": "student",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Register returned {response.status_code}"
    # Should redirect to dashboard after registration
    assert "/student" in response.url or "/dashboard" in response.url or "student" in response.text.lower(), (
        f"Student registration did not redirect properly. Final URL: {response.url}"
    )


def test_register_umkm() -> None:
    """PRD AUTH-01: UMKM registration creates account and is active immediately."""
    import uuid
    session = requests.Session()
    token = _get_csrf_token(session)

    unique_email = f"testumkm_{uuid.uuid4().hex[:8]}@gmail.com"
    response = session.post(
        f"{BASE_URL}/register",
        data={
            "name": "Test UMKM",
            "email": unique_email,
            "password": "password123",
            "password_confirmation": "password123",
            "role": "umkm",
            "business_name": "Test Business",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Register returned {response.status_code}"
    assert "/umkm" in response.url or "/dashboard" in response.url or "umkm" in response.text.lower(), (
        f"UMKM registration did not redirect properly. Final URL: {response.url}"
    )


def test_register_duplicate_email_rejected() -> None:
    """PRD AUTH-01: Duplicate email registration rejected."""
    session = requests.Session()
    token = _get_csrf_token(session)

    # Try registering with existing demo email
    response = session.post(
        f"{BASE_URL}/register",
        data={
            "name": "Duplicate User",
            "email": "talent@kivu.id",
            "password": "password123",
            "password_confirmation": "password123",
            "role": "student",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200
    assert "unique" in response.text.lower() or "taken" in response.text.lower() or "sudah" in response.text.lower() or "exist" in response.text.lower(), (
        "Duplicate email should show validation error"
    )


def test_logout() -> None:
    """PRD AUTH-02: Logout invalidates session."""
    session = requests.Session()
    token = _get_csrf_token(session)

    # Login first
    session.post(
        f"{BASE_URL}/login",
        data={
            "email": "talent@kivu.id",
            "password": "password",
            "_token": token,
        },
        timeout=30,
        allow_redirects=True,
    )

    # Get fresh CSRF token after login (session changed)
    fresh_token = _get_csrf_token(session)

    # Logout with fresh token
    response = session.post(
        f"{BASE_URL}/logout",
        data={"_token": fresh_token},
        timeout=30,
        allow_redirects=True,
    )
    assert response.status_code == 200, f"Logout returned {response.status_code}"

    # After logout, student routes should redirect to login
    response = session.get(f"{BASE_URL}/student", timeout=30, allow_redirects=False)
    assert response.status_code in [302, 401, 403], (
        f"After logout, /student returned {response.status_code} (expected redirect)"
    )


test_login_with_demo_student()
test_login_with_demo_umkm()
test_login_with_demo_admin()
test_login_wrong_password_rejected()
test_login_nonexistent_user_rejected()
test_register_student()
test_register_umkm()
test_register_duplicate_email_rejected()
test_logout()
