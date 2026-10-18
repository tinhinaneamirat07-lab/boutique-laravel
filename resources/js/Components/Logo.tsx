// Logo « miza » : le point du i est un rond coloré (CSS .logo .d::after)
export default function Logo({ size = 36 }: { size?: number }) {
    return (
        <span className="logo" style={size ? { fontSize: size } : undefined}>
            m<span className="d">ı</span>za
        </span>
    );
}
