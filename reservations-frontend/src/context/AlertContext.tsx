import React, { createContext, useContext, ReactNode, useState, useCallback } from 'react';
import { Modal, Button } from 'react-bootstrap';

interface AlertContextType {
  alert: (title: string, textHtml: React.ReactNode, onClick?: () => void, labelAccept?: string) => void;
  confirm: (
    title: string,
    textHtml: React.ReactNode,
    onClickYes?: () => void,
    onClickNo?: () => void,
    labelYes?: string,
    labelNo?: string
  ) => void;
}

interface ModalState {
  show: boolean;
  type: 'alert' | 'confirm';
  title: string;
  content: React.ReactNode;
  onAccept?: () => void;
  onCancel?: () => void;
  labelAccept?: string;
  labelCancel?: string;
}

const AlertContext = createContext<AlertContextType | undefined>(undefined);

export const AlertProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [modal, setModal] = useState<ModalState | null>(null);

  const handleClose = useCallback(() => setModal(null), []);

  const alert = (
    title: string,
    textHtml: React.ReactNode,
    onClick: (() => void) | null = null,
    labelAccept: string = 'Aceptar'
  ) => {
    setModal({
      show: true,
      type: 'alert',
      title,
      content: textHtml,
      onAccept: () => {
        handleClose();
        if (onClick) onClick();
      },
      labelAccept,
    });
  };

  const confirm = (
    title: string,
    textHtml: React.ReactNode,
    onClickYes: (() => void) | null = null,
    onClickNo: (() => void) | null = null,
    labelYes: string = 'Si',
    labelNo: string = 'No'
  ) => {
    setModal({
      show: true,
      type: 'confirm',
      title,
      content: textHtml,
      onAccept: () => {
        handleClose();
        if (onClickYes) onClickYes();
      },
      onCancel: () => {
        handleClose();
        if (onClickNo) onClickNo();
      },
      labelAccept: labelYes,
      labelCancel: labelNo,
    });
  };

  return (
    <AlertContext.Provider value={{ alert, confirm }}>
      {children}
      <Modal show={!!modal} onHide={handleClose} centered>
        <Modal.Header closeButton>
          <Modal.Title>{modal?.title}</Modal.Title>
        </Modal.Header>
        <Modal.Body>{modal?.content}</Modal.Body>
        <Modal.Footer>
          {modal?.type === 'confirm' && (
            <Button variant="secondary" onClick={modal.onCancel}>
              {modal.labelCancel || 'No'}
            </Button>
          )}
          <Button variant={modal?.type === 'alert' ? 'primary' : 'danger'} onClick={modal?.onAccept}>
            {modal?.labelAccept || (modal?.type === 'alert' ? 'Aceptar' : 'Si')}
          </Button>
        </Modal.Footer>
      </Modal>
    </AlertContext.Provider>
  );
};

export const useAlert = () => {
  const ctx = useContext(AlertContext);
  if (!ctx) throw new Error('useAlert must be used within an AlertProvider');
  return ctx.alert;
};

export const useConfirm = () => {
  const ctx = useContext(AlertContext);
  if (!ctx) throw new Error('useConfirm must be used within an AlertProvider');
  return ctx.confirm;
};
