import icons from "../../inc/icons.json"

// Same icon set the PHP templates use via apex_icon(). Markup is static and
// comes from our own JSON file, so injecting it is safe.
export default function Icon({ name, className = "size-6", strokeWidth = 1.5 }) {
  const paths = icons[name]
  if (!paths) return null

  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth={strokeWidth}
      strokeLinecap="round"
      strokeLinejoin="round"
      className={className}
      aria-hidden="true"
      focusable="false"
      dangerouslySetInnerHTML={{ __html: paths }}
    />
  )
}
