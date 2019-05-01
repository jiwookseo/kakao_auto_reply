# Kakaotalk Auto Reply
카카오톡에서 제공하는 플러스친구 API를 기반으로한 PHP 환경의 웹서버에서 동작하는 수련회 chatbot
by php language

**참고 : 카카오톡 API형 스마트 채팅은 2018년 12월부로 종료되었다.**



## I. 개요

php 언어를 이용해 웹서버 db를 연동한 동적 메세지를 보낼 수 있는 chatbot

행사 기간 중 모바일 바코드, 충전된 잔액 확인 기능, 각종 정보제공용 chatbot으로 사용하여
4일간 진행된 행사 기간 중 모든 기능을 도합하여 5000회 가량 사용되었다. (기능별 hits check)



## II. 구성

중요 구성으로는 keyboard.php 와 message.php가 있다.

* keyboard.php
  : chatbot의 버튼 리스트를 보여주는 소스코드
* message.php
  : 사용자의 버튼 클릭, 즉 메세지에 대한 response를 담고 있는 소스코드



## III. 주요기능

- 바코드 등록
  : signin html을 통해 db에 저장된 정보에 맞는 id 값을 할당하고,
    barcode bakery 를 통해 바코드 이미지를 생성해준다.

- 모바일 바코드
  : 앞서 말한 바코드 등록을 통해 생성된 바코드 이미지를 출력해주는 기능.  
- 잔액 확인
  : id에 저장된 금액 정보, 잔액을 알려준다.
- 이외 정적인 정보제공 기능들


## IV. Test 플러스친구

테스트해볼 수 있는 플러스친구 Seo_test

[카카오톡 chatbot test 용 플러스친구 메인페이지](https://pf.kakao.com/_ERQCxl)

[카카오톡 chatbot test 시작하기](https://pf.kakao.com/_ERQCxl/chat)

**현재 중단한 상태**

## V. 참고

[플러스친구 자동응답 API 공식문서](https://github.com/plusfriend/auto_reply)

[barcode bakery api](https://www.barcodebakery.com/en/resources/api/php/code128)
