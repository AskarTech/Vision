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
                vision: {
                    teal: "#00bdae",
                    "teal-dark": "#00968a",
                    "teal-mid": "#07a79a",
                    purple: "#7338a2",
                    accent: "#f94f51",
                    ink: "#0b1220",
                    border: "#e2e8f0",
                    bg: "#f8fafc",
                },
            },
            borderRadius: {
                "saas-card": "1rem",
            },
            boxShadow: {
                "saas-card":
                    "0 1px 3px rgba(15, 23, 42, 0.06), 0 4px 6px -2px rgba(15, 23, 42, 0.04)",
            },
            fontFamily: {
                sans: [
                    "Cairo",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
        },
    },
    plugins: [],
};
