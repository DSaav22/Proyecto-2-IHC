import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";
import { Mark, PrimaryButton, Screen } from "../components/ui";

export default function MyPlansPage() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [pending, setPending] = useState(false);

  async function handleLogout() {
    setPending(true);
    try {
      await logout();
    } finally {
      navigate("/login", { replace: true });
    }
  }

  return (
    <Screen>
      <Mark />
      <h1 className="text-4xl font-bold tracking-tight">Hola, {user?.name}</h1>
      <p className="mt-4 text-lg text-muted">
        Aquí aparecerán tus planes. Por ahora todavía no hay ninguno.
      </p>
      <div className="mt-8">
        <PrimaryButton type="button" onClick={handleLogout} disabled={pending}>
          Cerrar sesión
        </PrimaryButton>
      </div>
    </Screen>
  );
}
