import { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { RootState } from '@/store/store';
import { Brand } from '@/types/brand';

/**
 * This component injects dynamic CSS variables for the selected brand.
 * It expects the brand.styles attribute to be a JSON string with keys like "primary" and "secondary".
 */
const BrandDynamicStyles: React.FC = () => {
    const selectedBrand = useSelector((state: RootState) => (state.general as any)?.selectedBrand as Brand | undefined);

    useEffect(() => {
        let styleTag: HTMLStyleElement | null = null;
        if (selectedBrand?.styles) {
            try {
                const styles = JSON.parse(selectedBrand.styles);
                let cssVars = '';
                if (styles.primary) {
                    cssVars += `--brand-primary: ${styles.primary};`;
                }
                if (styles.secondary) {
                    cssVars += `--brand-secondary: ${styles.secondary};`;
                }
                if (cssVars) {
                    styleTag = document.createElement('style');
                    styleTag.setAttribute('data-brand-theme', String(selectedBrand.id));
                    styleTag.innerHTML = `:root { ${cssVars} }`;
                    document.head.appendChild(styleTag);
                }
            } catch (e) {
                // Invalid JSON, ignore
            }
        }
        return () => {
            if (styleTag) {
                styleTag.remove();
            }
        };
    }, [selectedBrand]);

    return null;
};

export default BrandDynamicStyles;
