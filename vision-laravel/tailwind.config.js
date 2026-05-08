/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: "#4F6EF7",
                    hover: "#3D58D3",
                },
                saas: {
                    bg: "#F5F7FF",
                    surface: "#FFFFFF",
                    text: "#1E293B",
                    muted: "#64748B",
                    success: "#7EE081",
                    info: "#93C5FD",
                    neutral: "#A5B4FC",
                    warning: "#FBBF24",
                    danger: "#F87171",
                },
            },
            borderRadius: {
                "saas-card": "1.5rem",
            },
            boxShadow: {
                "saas-card":
                    "0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05)",
            },
            fontFamily: {
                sans: ["Inter", "Tajawal", "Cairo", "ui-sans-serif", "system-ui", "sans-serif"],
            },
        },
    },
    plugins: [],
};
