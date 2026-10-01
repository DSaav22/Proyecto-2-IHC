import { Link } from "react-router-dom";
import { Mark, Screen } from "../components/ui";

export default function LandingPage() {
  return (
    <Screen>
      <Mark />
      <h1 className="text-[clamp(3.5rem,10vw,6.5rem)] font-bold leading-[0.9] tracking-[-0.05em]">
        Planazo
      </h1>
      <p className="mt-6 border-t border-line pt-5 text-[clamp(1.35rem,3vw,1.8rem)] leading-tight text-muted">
        Ayudar a un grupo
        <br />
        a organizar un plan.
      </p>
      <nav aria-label="Acceso" className="mt-10 flex flex-wrap gap-3">
        <Link
          to="/login"
          className="rounded-full bg-ink px-5 py-2.5 font-semibold text-accent"
        >
          Iniciar sesión
        </Link>
        <Link
          to="/register"
          className="rounded-full border border-ink px-5 py-2.5 font-semibold text-ink"
        >
          Crear cuenta
        </Link>
      </nav>
    </Screen>
  );
}
