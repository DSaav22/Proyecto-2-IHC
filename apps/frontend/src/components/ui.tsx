import type { ButtonHTMLAttributes, InputHTMLAttributes, ReactNode } from "react";
import { Link } from "react-router-dom";

export function Mark() {
  return (
    <Link
      to="/"
      className="mb-7 block w-fit rounded-full bg-ink px-2.5 py-1.5 text-[0.8rem] font-bold uppercase tracking-wide text-accent"
    >
      Planazo
    </Link>
  );
}

export function Screen({ children }: { children: ReactNode }) {
  return (
    <main className="mx-auto flex min-h-screen max-w-180 flex-col justify-center px-6 pb-18 pt-12">
      {children}
    </main>
  );
}

export function LoadingScreen() {
  return (
    <Screen>
      <p role="status" className="text-muted">
        Cargando…
      </p>
    </Screen>
  );
}

export function FormCard({ title, children }: { title: string; children: ReactNode }) {
  return (
    <Screen>
      <Mark />
      <h1 className="text-4xl font-bold tracking-tight">{title}</h1>
      <div className="mt-6 border-t border-line pt-6">{children}</div>
    </Screen>
  );
}

interface FieldProps extends InputHTMLAttributes<HTMLInputElement> {
  id: string;
  label: string;
  error?: string;
}

export function Field({ id, label, error, ...props }: FieldProps) {
  return (
    <div className="mb-4">
      <label htmlFor={id} className="mb-1 block text-sm font-semibold">
        {label}
      </label>
      <input
        id={id}
        aria-invalid={error ? true : undefined}
        aria-describedby={error ? `${id}-error` : undefined}
        className="w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:outline-2 focus:outline-ink"
        {...props}
      />
      {error ? (
        <p id={`${id}-error`} className="mt-1 text-sm text-red-700">
          {error}
        </p>
      ) : null}
    </div>
  );
}

export function ErrorAlert({ message }: { message: string | null }) {
  if (!message) return null;
  return (
    <p
      role="alert"
      className="mb-4 rounded-md border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-800"
    >
      {message}
    </p>
  );
}

export function SuccessNotice({ children }: { children: ReactNode }) {
  return (
    <div
      role="status"
      className="mb-4 rounded-md border border-line bg-white px-3 py-2 text-sm text-ink"
    >
      {children}
    </div>
  );
}

export function PrimaryButton({
  children,
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
  return (
    <button
      className="rounded-full bg-ink px-5 py-2.5 font-semibold text-accent disabled:opacity-60"
      {...props}
    >
      {children}
    </button>
  );
}

export const linkClass = "font-semibold underline underline-offset-2";
