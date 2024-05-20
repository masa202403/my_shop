```mermaid
flowchart TD

subgraph Sing in
index[トップ]
input[入力画面]
auth{認証}
user_home[ユーザホーム]

index-.->input-->auth-.->|OK|user_home
auth-.->|NG|input

end
```