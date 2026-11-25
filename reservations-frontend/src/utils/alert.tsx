import React from 'react';
import { confirmAlert } from 'react-confirm-alert';
import { Body, Content, Footer, Header, Title } from '../components/AlertBody';
import 'react-confirm-alert/src/react-confirm-alert.css';

export const modalAlert = (
  title: string,
  textHtml: React.ReactNode,
  onClick: (() => void) | null = null,
  labelAccept: string = 'Aceptar'
) =>
  confirmAlert({
    customUI: ({ onClose }: { onClose: () => void }) => (
      <Content>
        <Header>
          <Title>
            <h3>{title}</h3>
          </Title>
        </Header>
        <Body>{textHtml}</Body>
        <Footer>
          <button
            className="button"
            onClick={() => {
              onClose();
              if (onClick) {
                onClick();
              }
            }}
          >
            {labelAccept}
          </button>
        </Footer>
      </Content>
    ),
  });

export const modalConfirm = (
  title: string,
  textHtml: React.ReactNode,
  onClickYes: (() => void) | null = null,
  onClickNo: (() => void) | null = null,
  labelYes: string = 'Si',
  labelNo: string = 'No'
) =>
  confirmAlert({
    customUI: ({ onClose }: { onClose: () => void }) => (
      <Content>
        <Header>
          <Title>
            <h3>{title}</h3>
          </Title>
        </Header>
        <Body>{textHtml}</Body>
        <Footer className="button-group">
          <button
            className="button button-danger"
            onClick={() => {
              onClose();
              if (onClickYes) {
                onClickYes();
              }
            }}
          >
            {labelYes}
          </button>
          <button
            className="button"
            onClick={() => {
              onClose();
              if (onClickNo) {
                onClickNo();
              }
            }}
          >
            {labelNo}
          </button>
        </Footer>
      </Content>
    ),
  });
